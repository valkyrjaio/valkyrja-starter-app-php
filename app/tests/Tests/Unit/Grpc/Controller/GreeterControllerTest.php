<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Grpc\Controller;

use App\Grpc\Controller\GreeterController;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Grpc\Message\Call\Contract\ServiceCallContract;
use Valkyrja\Grpc\Message\Call\ServiceCall;
use Valkyrja\Grpc\Message\Cancellation\CancellationToken;
use Valkyrja\Grpc\Message\Enum\CancellationReason;
use Valkyrja\Grpc\Message\Enum\StatusCode;
use Valkyrja\Grpc\Routing\Data\Contract\RouteContract;

use function iterator_to_array;

final class GreeterControllerTest extends TestCase
{
    public function testSayHelloGreetsTheInboundMessage(): void
    {
        $call = ServiceCall::unary('/app.Greeter/SayHello', 'world');

        $response = GreeterController::sayHello(
            $this->container($call),
            self::createStub(RouteContract::class)
        );

        self::assertSame(StatusCode::OK, $response->getStatus()->getCode());
        self::assertSame(['Hello, world!'], iterator_to_array($response->getMessages(), false));
    }

    public function testSayHelloWithNoInboundMessage(): void
    {
        $call = new ServiceCall('/app.Greeter/SayHello');

        $response = GreeterController::sayHello(
            $this->container($call),
            self::createStub(RouteContract::class)
        );

        self::assertSame(['Hello, !'], iterator_to_array($response->getMessages(), false));
    }

    public function testSayHelloWithANonStringInboundMessage(): void
    {
        $call = ServiceCall::unary('/app.Greeter/SayHello', 42);

        $response = GreeterController::sayHello(
            $this->container($call),
            self::createStub(RouteContract::class)
        );

        self::assertSame(['Hello, !'], iterator_to_array($response->getMessages(), false));
    }

    public function testFanoutStreamsSeveralMessages(): void
    {
        $call = ServiceCall::unary('/app.Greeter/Fanout', 'world');

        $response = GreeterController::fanout(
            $this->container($call),
            self::createStub(RouteContract::class)
        );

        self::assertSame(StatusCode::OK, $response->getStatus()->getCode());
        self::assertSame(
            ['one: world', 'two: world', 'three: world'],
            iterator_to_array($response->getMessages(), false)
        );
    }

    public function testFanoutStopsAtTheNextMessageOnceCancelled(): void
    {
        $cancellation = new CancellationToken();

        $call = new ServiceCall(
            method: '/app.Greeter/Fanout',
            messages: ['world'],
            cancellation: $cancellation,
        );

        $response = GreeterController::fanout(
            $this->container($call),
            self::createStub(RouteContract::class)
        );

        $drained = [];

        foreach ($response->getMessages() as $message) {
            $drained[] = $message;

            $cancellation->cancel(CancellationReason::CLIENT_CANCELLED);
        }

        self::assertSame(['one: world'], $drained);
    }

    private function container(ServiceCallContract $call): Container
    {
        $container = new Container();

        $container->setSingleton(ServiceCallContract::class, $call);

        return $container;
    }
}
