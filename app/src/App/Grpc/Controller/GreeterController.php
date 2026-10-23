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
use Valkyrja\Grpc\Message\Status\Status;
use Valkyrja\Grpc\Routing\Attribute\Route;
use Valkyrja\Grpc\Routing\Attribute\Service;
use Valkyrja\Grpc\Routing\Data\Contract\RouteContract;

use function is_string;

#[Service(service: 'app.Greeter')]
final class GreeterController
{
    /**
     * Unary: one message in, one message out.
     */
    #[Route(name: 'SayHello', handler: [self::class, 'sayHello'])]
    public static function sayHello(ContainerContract $container, RouteContract $route): ServiceResponseContract
    {
        $call = $container->getSingleton(ServiceCallContract::class);
        $name = self::firstMessage($call);

        if ($name === null) {
            return ServiceResponse::of(Status::invalidArgument('The message must be a string.'));
        }

        return ServiceResponse::ok("Hello, $name!");
    }

    /**
     * Server streaming: one message in, several out, drained lazily through the call's cancellable
     * iterable so the stream stops at the next message once the client goes away.
     */
    #[Route(name: 'Fanout', handler: [self::class, 'fanout'], serverStreaming: true)]
    public static function fanout(ContainerContract $container, RouteContract $route): ServiceResponseContract
    {
        $call = $container->getSingleton(ServiceCallContract::class);
        $name = self::firstMessage($call);

        if ($name === null) {
            return ServiceResponse::of(Status::invalidArgument('The message must be a string.'));
        }

        $messages = [
            "one: $name",
            "two: $name",
            "three: $name",
        ];

        return ServiceResponse::ok()->withMessages($call->cancellable($messages));
    }

    /**
     * Get the first inbound message, an empty string when the call carried none, or null when the
     * message is not a string.
     */
    private static function firstMessage(ServiceCallContract $call): string|null
    {
        /** @psalm-suppress MixedAssignment Message payloads are deliberately agnostic */
        foreach ($call->getMessages() as $message) {
            return is_string($message)
                ? $message
                : null;
        }

        return '';
    }
}
