<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Grpc\Controller;

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Grpc\Message\Call\Contract\ServiceCallContract;
use Valkyrja\Grpc\Message\Response\Contract\ServiceResponseContract;
use Valkyrja\Grpc\Message\Response\ServiceResponse;
use Valkyrja\Grpc\Routing\Attribute\Method;
use Valkyrja\Grpc\Routing\Attribute\Service;
use Valkyrja\Grpc\Routing\Data\Contract\RouteContract;

use function is_string;

/**
 * A sample gRPC service.
 *
 * Messages are the port's own agnostic type, so a handler works with whatever the transport adapter
 * decoded — the framework never references a generated protobuf class.
 */
#[Service(service: 'app.Greeter')]
final class GreeterController
{
    /**
     * Unary: one message in, one message out.
     */
    #[Method(name: 'SayHello')]
    public static function sayHello(ContainerContract $container, RouteContract $route): ServiceResponseContract
    {
        $call = $container->getSingleton(ServiceCallContract::class);

        return ServiceResponse::ok('Hello, ' . self::firstMessage($call) . '!');
    }

    /**
     * Server streaming: one message in, several out, drained lazily through the call's cancellable
     * iterable so the stream stops at the next message once the client goes away.
     */
    #[Method(name: 'Fanout', serverStreaming: true)]
    public static function fanout(ContainerContract $container, RouteContract $route): ServiceResponseContract
    {
        $call = $container->getSingleton(ServiceCallContract::class);
        $name = self::firstMessage($call);

        $messages = (static function () use ($name): iterable {
            foreach (['one', 'two', 'three'] as $index) {
                yield "$index: $name";
            }
        })();

        return ServiceResponse::ok()->withMessages($call->cancellable($messages));
    }

    /**
     * Get the first inbound message as a string, or an empty string when the call carried none.
     */
    private static function firstMessage(ServiceCallContract $call): string
    {
        /** @psalm-suppress MixedAssignment Message payloads are deliberately agnostic */
        foreach ($call->getMessages() as $message) {
            return is_string($message)
                ? $message
                : '';
        }

        return '';
    }
}
