<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Queue\Provider;

use App\Queue\Data\AppContainerData;
use App\Queue\Data\AppEventData;
use App\Queue\Data\AppQueueRoutingData;
use App\Queue\Provider\AppQueueDataServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Queue\Routing\Data\QueueRoutingData;

final class AppQueueDataServiceProviderTest extends TestCase
{
    public function testPublishContainerData(): void
    {
        $container = new Container();

        AppQueueDataServiceProvider::publishContainerData($container);

        self::assertInstanceOf(AppContainerData::class, $container->getSingleton(ContainerData::class));
    }

    public function testPublishEventData(): void
    {
        $container = new Container();

        AppQueueDataServiceProvider::publishEventData($container);

        self::assertInstanceOf(AppEventData::class, $container->getSingleton(EventData::class));
    }

    public function testPublishQueueRoutingData(): void
    {
        $container = new Container();

        AppQueueDataServiceProvider::publishQueueRoutingData($container);

        self::assertInstanceOf(AppQueueRoutingData::class, $container->getSingleton(QueueRoutingData::class));
    }

    public function testPublishers(): void
    {
        self::assertSame(
            [
                ContainerData::class    => [AppQueueDataServiceProvider::class, 'publishContainerData'],
                EventData::class        => [AppQueueDataServiceProvider::class, 'publishEventData'],
                QueueRoutingData::class => [AppQueueDataServiceProvider::class, 'publishQueueRoutingData'],
            ],
            new AppQueueDataServiceProvider()->publishers()
        );
    }
}
