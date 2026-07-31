<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Queue\Job;

use App\Queue\Job\FlakyJob;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Queue\Message\Enum\JobResult;
use Valkyrja\Queue\Routing\Data\Contract\RouteContract;

final class FlakyJobTest extends TestCase
{
    public function testAlwaysAsksToBeRetried(): void
    {
        self::assertSame(
            JobResult::RETRY,
            FlakyJob::handle(new Container(), self::createStub(RouteContract::class))
        );
    }

    public function testTheAttributedMethodIsTheRouteCarrier(): void
    {
        // The #[Route] method declares the job; #[RouteHandler] points the
        // router at the real handler, so this body is never the dispatch path
        self::assertSame(JobResult::RETRY, new FlakyJob()->flake());
    }
}
