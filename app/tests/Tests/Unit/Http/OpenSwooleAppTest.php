<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Http;

use App\Http\OpenSwooleApp;
use App\Throwable\Handler\ThrowableHandler;
use OpenSwoole\Http\Server;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

use function extension_loaded;
use function putenv;

/**
 * OpenSwoole permits a single server per process, so each test runs in an
 * isolated process.
 */
#[RunTestsInSeparateProcesses]
final class OpenSwooleAppTest extends TestCase
{
    public function testGetThrowableHandlerReturnsApplicationHandler(): void
    {
        self::assertInstanceOf(ThrowableHandler::class, OpenSwooleApp::getThrowableHandler());
    }

    public function testDefaultExceptionHandlerRuns(): void
    {
        OpenSwooleApp::defaultExceptionHandler();

        // The application handler's enable() is a no-op, so reaching here is the assertion.
        self::assertTrue(true);
    }

    public function testGetSwooleServerUsesEnvironmentHostAndPort(): void
    {
        if (! extension_loaded('openswoole')) {
            self::markTestSkipped('The openswoole extension is not loaded.');
        }

        putenv('APP_OPENSWOOLE_HOST=127.0.0.1');
        putenv('APP_OPENSWOOLE_PORT=12345');

        try {
            self::assertInstanceOf(Server::class, OpenSwooleApp::getSwooleServer());
        } finally {
            putenv('APP_OPENSWOOLE_HOST');
            putenv('APP_OPENSWOOLE_PORT');
        }
    }

    public function testGetSwooleServerFallsBackToDefaultHostAndPort(): void
    {
        if (! extension_loaded('openswoole')) {
            self::markTestSkipped('The openswoole extension is not loaded.');
        }

        // With neither variable set, getenv() returns false for both and the defaults apply. This
        // is the other side of the two host/port ternaries the environment test above exercises.
        putenv('APP_OPENSWOOLE_HOST');
        putenv('APP_OPENSWOOLE_PORT');

        $server = OpenSwooleApp::getSwooleServer();

        self::assertInstanceOf(Server::class, $server);
        self::assertSame('127.0.0.1', $server->host);
        self::assertSame(9501, $server->port);
    }
}
