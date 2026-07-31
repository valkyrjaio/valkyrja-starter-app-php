<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Queue\Data;

use App\Queue\Data\AppQueueRoutingData;
use App\Queue\Job\SendWelcomeEmailJob;
use PHPUnit\Framework\TestCase;
use Valkyrja\Queue\Routing\Data\Contract\RouteContract;
use Valkyrja\Queue\Routing\Data\QueueRoutingData;

/**
 * Assert the sindri-generated queue-component {@see AppQueueRoutingData} is populated.
 *
 * Unlike the root PHPUnit suite — which tests the empty *.example.php stubs —
 * this job runs real generation first, so it is the only place the generated
 * queue cache is exercised as the framework would load it.
 */
final class AppQueueRoutingDataTest extends TestCase
{
    public function testGeneratesPopulatedQueueRoutingData(): void
    {
        $data = new AppQueueRoutingData();

        self::assertInstanceOf(QueueRoutingData::class, $data);
        self::assertNotEmpty($data->routes);

        // The application's own job, discovered from its queue route provider
        self::assertArrayHasKey(SendWelcomeEmailJob::NAME, $data->routes);

        foreach ($data->routes as $routeFactory) {
            self::assertInstanceOf(RouteContract::class, $routeFactory());
        }
    }

    public function testTheGeneratedRouteKeepsItsHandlerAndDescription(): void
    {
        $route = new AppQueueRoutingData()->routes[SendWelcomeEmailJob::NAME]();

        self::assertSame(SendWelcomeEmailJob::NAME, $route->getName());
        self::assertSame('Send a new user their welcome email', $route->getDescription());
        // The handler survives generation as a compile-time reference, not a
        // live binding — nothing in the cache evaluates application code
        self::assertSame([SendWelcomeEmailJob::class, 'handle'], $route->getHandler());
    }

    public function testTheGeneratedRouteCarriesNoRetryPolicy(): void
    {
        $route = new AppQueueRoutingData()->routes[SendWelcomeEmailJob::NAME]();

        // Retry policy rides on the job because the producer decides it
        self::assertSame([], $route->getRouteMatchedMiddleware());
        self::assertSame([], $route->getRouteDispatchedMiddleware());
        self::assertSame([], $route->getThrowableCaughtMiddleware());
        self::assertSame([], $route->getSettlingResultMiddleware());
        self::assertSame([], $route->getResultSettledMiddleware());
    }
}
