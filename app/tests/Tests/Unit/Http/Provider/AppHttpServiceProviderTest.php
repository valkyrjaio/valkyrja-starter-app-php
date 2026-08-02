<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Http\Provider;

use App\Http\Controller\HomeController;
use App\Http\Controller\RoutingPermutationsController;
use App\Http\Provider\AppHttpServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;

final class AppHttpServiceProviderTest extends TestCase
{
    public function testPublishHomeController(): void
    {
        $container = new Container();
        $container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));
        $container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        AppHttpServiceProvider::publishHomeController($container);

        self::assertInstanceOf(HomeController::class, $container->getSingleton(HomeController::class));
    }

    public function testPublishRoutingPermutationsController(): void
    {
        $container = new Container();
        $container->setSingleton(ServerRequestContract::class, self::createStub(ServerRequestContract::class));
        $container->setSingleton(ResponseFactoryContract::class, self::createStub(ResponseFactoryContract::class));

        AppHttpServiceProvider::publishRoutingPermutationsController($container);

        self::assertInstanceOf(
            RoutingPermutationsController::class,
            $container->getSingleton(RoutingPermutationsController::class)
        );
    }

    public function testPublishers(): void
    {
        self::assertSame(
            [
                HomeController::class                => [AppHttpServiceProvider::class, 'publishHomeController'],
                RoutingPermutationsController::class => [AppHttpServiceProvider::class, 'publishRoutingPermutationsController'],
            ],
            new AppHttpServiceProvider()->publishers(),
        );
    }
}
