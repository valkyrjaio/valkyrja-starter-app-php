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

use App\Http\FrankenPhpApp;
use App\Throwable\Handler\ThrowableHandler;
use PHPUnit\Framework\TestCase;

final class FrankenPhpAppTest extends TestCase
{
    public function testGetThrowableHandlerReturnsApplicationHandler(): void
    {
        self::assertInstanceOf(ThrowableHandler::class, FrankenPhpApp::getThrowableHandler());
    }

    public function testDefaultExceptionHandlerRuns(): void
    {
        FrankenPhpApp::defaultExceptionHandler();

        // The application handler's enable() is a no-op, so reaching here is the assertion.
        self::assertTrue(true);
    }
}
