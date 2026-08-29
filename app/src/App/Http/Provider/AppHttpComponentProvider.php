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

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Application\Provider\HttpApplicationComponentProvider;
use Valkyrja\Container\Provider\ContainerServiceProvider;

final class AppHttpComponentProvider implements ComponentProviderContract
{
    /**
     * @inheritDoc
     */
    public static function publish(ApplicationContract $app): void
    {
        $container = $app->getContainer();

        if ($app->getDebugMode()) {
            ContainerServiceProvider::publishData(container: $container);

            return;
        }

        AppHttpDataServiceProvider::publishContainerData(container: $container);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getComponentProviders(ApplicationContract $app): array
    {
        return [
            new HttpApplicationComponentProvider(),
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainerProviders(ApplicationContract $app): array
    {
        return [
            new AppHttpDataServiceProvider(),
            new AppHttpServiceProvider(),
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEventProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCliProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHttpProviders(ApplicationContract $app): array
    {
        return [
            new AppHttpRouteProvider(),
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getGrpcProviders(ApplicationContract $app): array
    {
        return [];
    }
}
