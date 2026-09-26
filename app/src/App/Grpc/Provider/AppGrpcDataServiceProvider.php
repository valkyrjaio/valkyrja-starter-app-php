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

use App\Grpc\Data\AppContainerData;
use App\Grpc\Data\AppEventData;
use App\Grpc\Data\AppGrpcRoutingData;
use Override;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Grpc\Routing\Data\GrpcRoutingData;

final class AppGrpcDataServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the container data.
     */
    public static function publishContainerData(ContainerContract $container): void
    {
        $container->setSingleton(ContainerData::class, new AppContainerData());
    }

    /**
     * Publish the event data.
     */
    public static function publishEventData(ContainerContract $container): void
    {
        $container->setSingleton(EventData::class, new AppEventData());
    }

    /**
     * Publish the gRPC routing data.
     */
    public static function publishGrpcRoutingData(ContainerContract $container): void
    {
        $container->setSingleton(GrpcRoutingData::class, new AppGrpcRoutingData());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ContainerData::class   => [self::class, 'publishContainerData'],
            EventData::class       => [self::class, 'publishEventData'],
            GrpcRoutingData::class => [self::class, 'publishGrpcRoutingData'],
        ];
    }
}
