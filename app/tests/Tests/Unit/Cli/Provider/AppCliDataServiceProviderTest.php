<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Cli\Provider;

use App\Cli\Data\AppCliRoutingData;
use App\Cli\Data\AppContainerData;
use App\Cli\Data\AppEventData;
use App\Cli\Data\AppHttpRoutingData;
use App\Cli\Provider\AppCliDataServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class AppCliDataServiceProviderTest extends TestCase
{
    public function testPublishContainerData(): void
    {
        $container = new Container();

        AppCliDataServiceProvider::publishContainerData($container);

        self::assertInstanceOf(AppContainerData::class, $container->getSingleton(ContainerData::class));
    }

    public function testPublishEventData(): void
    {
        $container = new Container();

        AppCliDataServiceProvider::publishEventData($container);

        self::assertInstanceOf(AppEventData::class, $container->getSingleton(EventData::class));
    }

    public function testPublishCliRoutingData(): void
    {
        $container = new Container();

        AppCliDataServiceProvider::publishCliRoutingData($container);

        self::assertInstanceOf(AppCliRoutingData::class, $container->getSingleton(CliRoutingData::class));
    }

    public function testPublishHttpRoutingData(): void
    {
        $container = new Container();

        AppCliDataServiceProvider::publishHttpRoutingData($container);

        self::assertInstanceOf(AppHttpRoutingData::class, $container->getSingleton(HttpRoutingData::class));
    }

    public function testPublishers(): void
    {
        self::assertSame(
            [
                ContainerData::class   => [AppCliDataServiceProvider::class, 'publishContainerData'],
                EventData::class       => [AppCliDataServiceProvider::class, 'publishEventData'],
                CliRoutingData::class  => [AppCliDataServiceProvider::class, 'publishCliRoutingData'],
                HttpRoutingData::class => [AppCliDataServiceProvider::class, 'publishHttpRoutingData'],
            ],
            new AppCliDataServiceProvider()->publishers(),
        );
    }
}
