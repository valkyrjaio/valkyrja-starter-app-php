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

use App\Http\App;
use App\Throwable\Handler\ThrowableHandler;
use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
    public function testGetThrowableHandlerReturnsApplicationHandler(): void
    {
        self::assertInstanceOf(ThrowableHandler::class, App::getThrowableHandler());
    }

    public function testDefaultExceptionHandlerRuns(): void
    {
        App::defaultExceptionHandler();

        // The application handler's enable() is a no-op, so reaching here is the assertion.
        self::assertTrue(true);
    }
}
