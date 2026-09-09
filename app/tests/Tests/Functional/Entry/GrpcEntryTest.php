<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Functional\Entry;

use PHPUnit\Framework\TestCase;

use function dirname;
use function escapeshellarg;
use function exec;
use function implode;

use const PHP_BINARY;

/**
 * End-to-end smoke test for the gRPC entry point (`app/bin/grpc`).
 *
 * Runs the real entry in a subprocess and dispatches calls through the same worker lifecycle a
 * transport adapter drives — catching regressions in the entry wiring, provider bootstrap, service
 * map, or pipeline that class-level tests miss.
 *
 * The app config runs in debug mode, so this exercises the attribute scan. The generated service
 * map is asserted separately, against the real emitted classes, by the sindri-phpunit suite.
 */
final class GrpcEntryTest extends TestCase
{
    public function testGrpcBootsAndServesAUnaryCall(): void
    {
        $output = $this->runEntry(['/app.Greeter/SayHello=world']);

        self::assertStringContainsString('/app.Greeter/SayHello OK Hello, world!', $output);
    }

    public function testGrpcServesAServerStreamingCall(): void
    {
        $output = $this->runEntry(['/app.Greeter/Fanout=stream']);

        // Every message the handler yields reaches the drain, in order.
        self::assertStringContainsString(
            '/app.Greeter/Fanout OK one: stream two: stream three: stream',
            $output
        );
    }

    public function testAnUnknownMethodIsUnimplemented(): void
    {
        $output = $this->runEntry(['/app.Greeter/Nope=x']);

        self::assertStringContainsString('/app.Greeter/Nope UNIMPLEMENTED', $output);
    }

    public function testTheWorkerServesSeveralCallsFromOneBootstrap(): void
    {
        $output = $this->runEntry([
            '/app.Greeter/SayHello=first',
            '/app.Greeter/SayHello=second',
            '/app.Greeter/Fanout=third',
        ]);

        // Each call gets its own isolated child container, so nothing bleeds between them.
        self::assertStringContainsString('/app.Greeter/SayHello OK Hello, first!', $output);
        self::assertStringContainsString('/app.Greeter/SayHello OK Hello, second!', $output);
        self::assertStringContainsString('/app.Greeter/Fanout OK one: third', $output);
    }

    /**
     * Run the gRPC entry with the given call arguments and return its output.
     *
     * @param non-empty-string[] $arguments
     */
    private function runEntry(array $arguments): string
    {
        $entry = dirname(__DIR__, 5) . '/app/bin/grpc';

        $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($entry);

        foreach ($arguments as $argument) {
            $command .= ' ' . escapeshellarg($argument);
        }

        $command .= ' 2>&1';

        $output   = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $stdout = implode("\n", $output);

        self::assertSame(0, $exitCode, "app/bin/grpc did not boot cleanly:\n" . $stdout);
        self::assertStringNotContainsString('Fatal error', $stdout);
        self::assertStringNotContainsString('Uncaught', $stdout);

        return $stdout;
    }
}
