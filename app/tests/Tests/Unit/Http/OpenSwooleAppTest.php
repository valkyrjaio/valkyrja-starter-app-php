<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
}
