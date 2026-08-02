<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Http\Provider;

use App\Http\Controller\HomeController;
use App\Http\Controller\RoutingPermutationsController;
use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;

final class AppHttpServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the home controller.
     */
    public static function publishHomeController(ContainerContract $container): void
    {
        $container->setSingleton(
            HomeController::class,
            new HomeController(
                $container->getSingleton(ServerRequestContract::class),
                $container->getSingleton(ResponseFactoryContract::class)
            )
        );
    }

    /**
     * Publish the routing permutations controller.
     */
    public static function publishRoutingPermutationsController(ContainerContract $container): void
    {
        $container->setSingleton(
            RoutingPermutationsController::class,
            new RoutingPermutationsController(
                $container->getSingleton(ServerRequestContract::class),
                $container->getSingleton(ResponseFactoryContract::class)
            )
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            HomeController::class                => [self::class, 'publishHomeController'],
            RoutingPermutationsController::class => [self::class, 'publishRoutingPermutationsController'],
        ];
    }
}
