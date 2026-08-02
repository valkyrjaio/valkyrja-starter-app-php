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

use App\Http\RoadRunnerApp;
use App\Throwable\Handler\ThrowableHandler;
use PHPUnit\Framework\TestCase;

final class RoadRunnerAppTest extends TestCase
{
    public function testGetThrowableHandlerReturnsApplicationHandler(): void
    {
        self::assertInstanceOf(ThrowableHandler::class, RoadRunnerApp::getThrowableHandler());
    }

    public function testDefaultExceptionHandlerRuns(): void
    {
        RoadRunnerApp::defaultExceptionHandler();

        // The application handler's enable() is a no-op, so reaching here is the assertion.
        self::assertTrue(true);
    }
}
