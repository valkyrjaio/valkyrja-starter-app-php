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

use App\Http\Data\AppContainerData;
use App\Http\Data\AppEventData;
use App\Http\Data\AppHttpRoutingData;
use Override;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class AppHttpDataServiceProvider implements ServiceProviderContract
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
     * Publish the http routing data.
     */
    public static function publishHttpRoutingData(ContainerContract $container): void
    {
        $container->setSingleton(HttpRoutingData::class, new AppHttpRoutingData());
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
            HttpRoutingData::class => [self::class, 'publishHttpRoutingData'],
        ];
    }
}
