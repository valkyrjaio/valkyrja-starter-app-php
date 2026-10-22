<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Queue\Provider;

use App\Queue\Data\AppContainerData;
use App\Queue\Data\AppEventData;
use App\Queue\Data\AppQueueRoutingData;
use Override;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Queue\Routing\Data\QueueRoutingData;

final class AppQueueDataServiceProvider implements ServiceProviderContract
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
     * Publish the queue routing data.
     */
    public static function publishQueueRoutingData(ContainerContract $container): void
    {
        $container->setSingleton(QueueRoutingData::class, new AppQueueRoutingData());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ContainerData::class    => [self::class, 'publishContainerData'],
            EventData::class        => [self::class, 'publishEventData'],
            QueueRoutingData::class => [self::class, 'publishQueueRoutingData'],
        ];
    }
}
