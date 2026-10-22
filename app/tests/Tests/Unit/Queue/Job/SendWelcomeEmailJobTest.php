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

use App\Queue\Job\SendWelcomeEmailJob;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Queue\Message\Enum\JobResult;
use Valkyrja\Queue\Message\Job\Contract\JobContract;
use Valkyrja\Queue\Message\Job\Factory\JobFactory;
use Valkyrja\Queue\Routing\Data\Contract\RouteContract;

final class SendWelcomeEmailJobTest extends TestCase
{
    public function testAWellFormedPayloadAcknowledges(): void
    {
        self::assertSame(JobResult::ACK, $this->handle(['user_id' => 42]));
    }

    public function testAMalformedPayloadFailsRatherThanRetries(): void
    {
        // Retrying would reproduce the same failure until the budget ran out
        self::assertSame(JobResult::FAIL, $this->handle(['user_id' => 'not-an-int']));
    }

    public function testAMissingPayloadFieldFails(): void
    {
        self::assertSame(JobResult::FAIL, $this->handle([]));
    }

    public function testTheAttributedMethodIsTheRouteCarrier(): void
    {
        // The #[Route] method declares the job; #[RouteHandler] points the
        // router at the real handler, so this body is never the dispatch path
        self::assertSame(JobResult::ACK, new SendWelcomeEmailJob()->send());
    }

    /**
     * @param array<array-key, mixed> $payload
     */
    private function handle(array $payload): JobResult
    {
        $container = new Container();

        $container->setSingleton(
            JobContract::class,
            new JobFactory()->create(SendWelcomeEmailJob::NAME, $payload)
        );

        return SendWelcomeEmailJob::handle($container, self::createStub(RouteContract::class));
    }
}
