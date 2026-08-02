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

use App\Http\Data\AppContainerData;
use App\Http\Data\AppEventData;
use App\Http\Data\AppHttpRoutingData;
use App\Http\Provider\AppHttpDataServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class AppHttpDataServiceProviderTest extends TestCase
{
    public function testPublishContainerData(): void
    {
        $container = new Container();

        AppHttpDataServiceProvider::publishContainerData($container);

        self::assertInstanceOf(AppContainerData::class, $container->getSingleton(ContainerData::class));
    }

    public function testPublishEventData(): void
    {
        $container = new Container();

        AppHttpDataServiceProvider::publishEventData($container);

        self::assertInstanceOf(AppEventData::class, $container->getSingleton(EventData::class));
    }

    public function testPublishHttpRoutingData(): void
    {
        $container = new Container();

        AppHttpDataServiceProvider::publishHttpRoutingData($container);

        self::assertInstanceOf(AppHttpRoutingData::class, $container->getSingleton(HttpRoutingData::class));
    }

    public function testPublishers(): void
    {
        self::assertSame(
            [
                ContainerData::class   => [AppHttpDataServiceProvider::class, 'publishContainerData'],
                EventData::class       => [AppHttpDataServiceProvider::class, 'publishEventData'],
                HttpRoutingData::class => [AppHttpDataServiceProvider::class, 'publishHttpRoutingData'],
            ],
            new AppHttpDataServiceProvider()->publishers(),
        );
    }
}
