<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Grpc\Provider;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Application\Provider\GrpcApplicationComponentProvider;
use Valkyrja\Container\Provider\ContainerServiceProvider;

final class AppGrpcComponentProvider implements ComponentProviderContract
{
    /**
     * Publish the container data, scanning in debug mode and loading the generated cache otherwise.
     */
    public static function publish(ApplicationContract $app): void
    {
        $container = $app->getContainer();

        if ($app->getDebugMode()) {
            ContainerServiceProvider::publishData(container: $container);

            return;
        }

        AppGrpcDataServiceProvider::publishContainerData(container: $container);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getComponentProviders(ApplicationContract $app): array
    {
        return [
            new GrpcApplicationComponentProvider(),
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainerProviders(ApplicationContract $app): array
    {
        return [
            new AppGrpcDataServiceProvider(),
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
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getGrpcProviders(ApplicationContract $app): array
    {
        return [
            new AppGrpcRouteProvider(),
        ];
    }
}
