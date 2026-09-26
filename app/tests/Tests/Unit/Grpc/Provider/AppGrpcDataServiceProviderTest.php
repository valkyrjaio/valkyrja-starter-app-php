<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Grpc\Provider;

use App\Grpc\Data\AppContainerData;
use App\Grpc\Data\AppEventData;
use App\Grpc\Data\AppGrpcRoutingData;
use App\Grpc\Provider\AppGrpcDataServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Grpc\Routing\Data\GrpcRoutingData;

final class AppGrpcDataServiceProviderTest extends TestCase
{
    public function testExpectedPublishers(): void
    {
        $publishers = new AppGrpcDataServiceProvider()->publishers();

        self::assertArrayHasKey(ContainerData::class, $publishers);
        self::assertArrayHasKey(EventData::class, $publishers);
        self::assertArrayHasKey(GrpcRoutingData::class, $publishers);
    }

    public function testPublishContainerData(): void
    {
        $container = new Container();

        AppGrpcDataServiceProvider::publishContainerData($container);

        self::assertInstanceOf(AppContainerData::class, $container->getSingleton(ContainerData::class));
    }

    public function testPublishEventData(): void
    {
        $container = new Container();

        AppGrpcDataServiceProvider::publishEventData($container);

        self::assertInstanceOf(AppEventData::class, $container->getSingleton(EventData::class));
    }

    public function testPublishGrpcRoutingData(): void
    {
        $container = new Container();

        AppGrpcDataServiceProvider::publishGrpcRoutingData($container);

        self::assertInstanceOf(AppGrpcRoutingData::class, $container->getSingleton(GrpcRoutingData::class));
    }
}
