<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Grpc\Data;

use App\Grpc\Controller\GreeterController;
use App\Grpc\Data\AppGrpcRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Grpc\Routing\Data\Contract\RouteContract;
use Valkyrja\Grpc\Routing\Data\GrpcRoutingData;

/**
 * Assert the sindri-generated {@see AppGrpcRoutingData} is correct and populated.
 *
 * Unlike the stub-based phpunit suite (which copies the empty *.example.php stubs and only asserts
 * the class is a GrpcRoutingData), this suite runs against the real class emitted by
 * `sindri data:generate` and asserts the application's gRPC methods are actually present, keyed
 * exactly as the wire spells them.
 */
final class AppGrpcRoutingDataTest extends TestCase
{
    public function testGeneratesPopulatedRoutingData(): void
    {
        $data = new AppGrpcRoutingData();

        self::assertInstanceOf(GrpcRoutingData::class, $data);

        // Both methods on the Greeter service.
        self::assertCount(2, $data->routes);
        self::assertArrayHasKey('/app.Greeter/SayHello', $data->routes);
        self::assertArrayHasKey('/app.Greeter/Fanout', $data->routes);

        // Every route entry is a lazy factory that resolves to a route.
        foreach ($data->routes as $routeFactory) {
            self::assertInstanceOf(RouteContract::class, $routeFactory());
        }
    }

    public function testGeneratesTheServiceAndMethodNameSplitFromTheWireKey(): void
    {
        $data = new AppGrpcRoutingData();

        $sayHello = ($data->routes['/app.Greeter/SayHello'])();

        self::assertSame('/app.Greeter/SayHello', $sayHello->getMethod());
        self::assertSame('app.Greeter', $sayHello->getService());
        self::assertSame('SayHello', $sayHello->getMethodName());
    }

    public function testGeneratesTheAttributedMethodAsTheHandler(): void
    {
        $data = new AppGrpcRoutingData();

        self::assertSame(
            [GreeterController::class, 'sayHello'],
            ($data->routes['/app.Greeter/SayHello'])()->getHandler()
        );
        self::assertSame(
            [GreeterController::class, 'fanout'],
            ($data->routes['/app.Greeter/Fanout'])()->getHandler()
        );
    }

    public function testGeneratesTheStreamingFlagsAsDeclared(): void
    {
        $data = new AppGrpcRoutingData();

        $sayHello = ($data->routes['/app.Greeter/SayHello'])();
        $fanout   = ($data->routes['/app.Greeter/Fanout'])();

        // Unary: neither flag set.
        self::assertFalse($sayHello->isClientStreaming());
        self::assertFalse($sayHello->isServerStreaming());

        // Server streaming: only the server flag, so the call keeps the buffered dispatch model.
        self::assertFalse($fanout->isClientStreaming());
        self::assertTrue($fanout->isServerStreaming());
    }

    public function testGeneratesNoPerRouteMiddlewareWhereNoneIsDeclared(): void
    {
        $data = new AppGrpcRoutingData();

        $route = ($data->routes['/app.Greeter/SayHello'])();

        self::assertSame([], $route->getRouteMatchedMiddleware());
        self::assertSame([], $route->getRouteDispatchedMiddleware());
        self::assertSame([], $route->getThrowableCaughtMiddleware());
        self::assertSame([], $route->getSendingResponseMiddleware());
        self::assertSame([], $route->getResponseSentMiddleware());
    }
}
