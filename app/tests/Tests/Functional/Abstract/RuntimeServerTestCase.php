<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Functional\Abstract;

use PHPUnit\Framework\TestCase;

use function dirname;
use function explode;
use function fclose;
use function file_get_contents;
use function fsockopen;
use function getenv;
use function is_resource;
use function microtime;
use function preg_match;
use function proc_close;
use function proc_get_status;
use function proc_open;
use function proc_terminate;
use function stream_context_create;
use function stream_get_contents;
use function stream_set_blocking;
use function stream_socket_get_name;
use function stream_socket_server;
use function strlen;
use function usleep;

/**
 * Base case for runtime end-to-end tests.
 *
 * Boots the real application under a given HTTP runtime in a subprocess, waits
 * for it to accept connections, exposes a simple GET helper, and always tears
 * the process down — so subclasses assert on the response an actual HTTP request
 * produces, exercising the entry wiring end to end.
 */
abstract class RuntimeServerTestCase extends TestCase
{
    protected int $port = 0;

    /** @var resource|null */
    private $process;

    /** @var array<int, resource> */
    private array $pipes = [];

    protected function tearDown(): void
    {
        $this->stopServer();
    }

    /**
     * Get the application root directory (the repository root).
     */
    protected function appRoot(): string
    {
        return dirname(__DIR__, 5);
    }

    /**
     * Reserve a free localhost TCP port.
     */
    protected function findFreePort(): int
    {
        $server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);

        self::assertIsResource($server, "Unable to reserve a free port: $errstr ($errno)");

        $name = stream_socket_get_name($server, false);

        fclose($server);

        self::assertNotFalse($name, 'Unable to resolve the reserved port name.');

        return (int) explode(':', $name)[1];
    }

    /**
     * Start a server process from the given command and wait for it to listen.
     *
     * @param list<string>          $command The command and its arguments
     * @param array<string, string> $env     Extra environment variables
     */
    protected function startServer(array $command, array $env = []): void
    {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            $command,
            $descriptors,
            $this->pipes,
            $this->appRoot(),
            $env === [] ? null : [...getenv(), ...$env]
        );

        self::assertIsResource($process, 'Unable to start the server process.');

        $this->process = $process;

        foreach ([1, 2] as $fd) {
            stream_set_blocking($this->pipes[$fd], false);
        }

        $this->waitForPort();
    }

    /**
     * Wait for the reserved port to accept a connection.
     */
    protected function waitForPort(float $timeoutSeconds = 10.0): void
    {
        $deadline = microtime(true) + $timeoutSeconds;

        while (microtime(true) < $deadline) {
            $connection = @fsockopen('127.0.0.1', $this->port, $errno, $errstr, 0.2);

            if (is_resource($connection)) {
                fclose($connection);

                return;
            }

            if (! $this->isProcessRunning()) {
                self::fail("Server process exited before listening:\n" . $this->drainOutput());
            }

            usleep(100_000);
        }

        self::fail("Server did not start listening on port {$this->port} in time:\n" . $this->drainOutput());
    }

    /**
     * Perform a GET request against the running server.
     */
    protected function httpGet(string $path): string
    {
        $context = stream_context_create([
            'http' => [
                'ignore_errors' => true,
                'timeout'       => 5,
            ],
        ]);

        $body = @file_get_contents("http://127.0.0.1:{$this->port}{$path}", false, $context);

        if ($body === false) {
            self::fail("Request to $path failed:\n" . $this->drainOutput());
        }

        return $body;
    }

    /**
     * Perform a request against the running server and return its status code and body.
     *
     * @param non-empty-string $method The request method
     *
     * @return array{status: int, body: string}
     */
    protected function httpRequest(string $path, string $method = 'GET'): array
    {
        $context = stream_context_create([
            'http' => [
                'method'        => $method,
                'ignore_errors' => true,
                'timeout'       => 5,
            ],
        ]);

        $body = @file_get_contents("http://127.0.0.1:{$this->port}{$path}", false, $context);

        if ($body === false) {
            self::fail("Request to $path failed:\n" . $this->drainOutput());
        }

        $status = 0;

        /** @var string[] $responseHeaders file_get_contents populates this in the local scope */
        $responseHeaders = $http_response_header ?? [];

        foreach ($responseHeaders as $header) {
            if (preg_match('/^HTTP\/[\d.]+\s+(\d{3})/', $header, $matches) === 1) {
                $status = (int) $matches[1];
            }
        }

        return [
            'status' => $status,
            'body'   => $body,
        ];
    }

    /**
     * Perform a request with a body against the running server.
     *
     * @param non-empty-string $path        The request path
     * @param string           $body        The request body
     * @param non-empty-string $method      The request method
     * @param non-empty-string $contentType The request content type
     *
     * @return array{status: int, body: string}
     */
    protected function httpSend(
        string $path,
        string $body,
        string $method = 'POST',
        string $contentType = 'application/json',
    ): array {
        $context = stream_context_create([
            'http' => [
                'method'        => $method,
                'header'        => "Content-Type: $contentType\r\nContent-Length: " . strlen($body),
                'content'       => $body,
                'ignore_errors' => true,
                'timeout'       => 5,
            ],
        ]);

        $responseBody = @file_get_contents("http://127.0.0.1:{$this->port}{$path}", false, $context);

        if ($responseBody === false) {
            self::fail("Request to $path failed:\n" . $this->drainOutput());
        }

        $status = 0;

        /** @var string[] $responseHeaders file_get_contents populates this in the local scope */
        $responseHeaders = $http_response_header ?? [];

        foreach ($responseHeaders as $header) {
            if (preg_match('/^HTTP\/[\d.]+\s+(\d{3})/', $header, $matches) === 1) {
                $status = (int) $matches[1];
            }
        }

        return [
            'status' => $status,
            'body'   => $responseBody,
        ];
    }

    /**
     * Determine whether the server process is still running.
     */
    private function isProcessRunning(): bool
    {
        if (! is_resource($this->process)) {
            return false;
        }

        $status = proc_get_status($this->process);

        return $status['running'] ?? false;
    }

    /**
     * Read whatever the process has written to stdout and stderr so far.
     */
    private function drainOutput(): string
    {
        $output = '';

        foreach ($this->pipes as $pipe) {
            if (is_resource($pipe)) {
                $output .= (string) @stream_get_contents($pipe);
            }
        }

        return $output;
    }

    /**
     * Terminate the server process and close its pipes.
     *
     * Sends SIGTERM, waits briefly for a graceful exit, then forces SIGKILL so a
     * runtime with a long shutdown grace period (e.g. FrankenPHP) cannot hang the
     * teardown.
     */
    private function stopServer(): void
    {
        if (! is_resource($this->process)) {
            $this->pipes = [];

            return;
        }

        proc_terminate($this->process);

        $deadline = microtime(true) + 3.0;

        while (microtime(true) < $deadline) {
            $status = proc_get_status($this->process);

            if (! ($status['running'] ?? false)) {
                break;
            }

            usleep(50_000);
        }

        $status = proc_get_status($this->process);

        if ($status['running'] ?? false) {
            proc_terminate($this->process, 9);
        }

        foreach ($this->pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }

        proc_close($this->process);

        $this->process = null;
        $this->pipes   = [];
    }
}
