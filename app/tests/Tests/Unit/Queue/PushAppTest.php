<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Queue;

use App\Queue\PushApp;
use App\Throwable\Handler\ThrowableHandler;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Queue\Message\Enum\JobResult;

final class PushAppTest extends TestCase
{
    public function testGetThrowableHandlerReturnsApplicationHandler(): void
    {
        self::assertInstanceOf(ThrowableHandler::class, PushApp::getThrowableHandler());
    }

    public function testDefaultExceptionHandlerRuns(): void
    {
        PushApp::defaultExceptionHandler();

        // The application handler's enable() is a no-op, so reaching here is the assertion.
        self::assertTrue(true);
    }

    public function testTheSettlementStatusMappingIsInherited(): void
    {
        // The processor reads the status as the settlement, so the app must not
        // drift from the framework's mapping
        self::assertSame(StatusCode::NO_CONTENT, PushApp::respond(JobResult::ACK)->getStatusCode());
        self::assertSame(StatusCode::SERVICE_UNAVAILABLE, PushApp::respond(JobResult::RETRY)->getStatusCode());
    }
}
