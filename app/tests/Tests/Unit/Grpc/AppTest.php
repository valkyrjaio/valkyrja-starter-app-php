<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Grpc;

use App\Grpc\App;
use App\Throwable\Handler\ThrowableHandler;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Entry\Abstract\WorkerGrpc;

use function is_subclass_of;

final class AppTest extends TestCase
{
    public function testExtendsTheWorkerEntry(): void
    {
        self::assertTrue(is_subclass_of(App::class, WorkerGrpc::class));
    }

    public function testGetThrowableHandler(): void
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
