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

use App\Grpc\Provider\AppGrpcComponentProvider;
use App\Grpc\Provider\AppGrpcDataServiceProvider;
use App\Grpc\Provider\AppGrpcRouteProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\GrpcApplicationComponentProvider;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;

final class AppGrpcComponentProviderTest extends TestCase
{
    public function testPublishInProductionUsesAppContainerData(): void
    {
        $container = new Container();
        $app       = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);
        $app->method('getDebugMode')->willReturn(false);

        AppGrpcComponentProvider::publish($app);

        self::assertTrue($container->isSingletonInstance(ContainerData::class));
    }

    public function testPublishInDebugModePublishesContainerData(): void
    {
        $container = new Container();
        $app       = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);
        $app->method('getDebugMode')->willReturn(true);
        $app->method('getContainerProviders')->willReturn([]);
        $container->setSingleton(ApplicationContract::class, $app);

        AppGrpcComponentProvider::publish($app);

        self::assertTrue($container->isSingletonInstance(ContainerData::class));
    }

    public function testGetComponentProviders(): void
    {
        $providers = new AppGrpcComponentProvider()->getComponentProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(GrpcApplicationComponentProvider::class, $providers[0]);
    }

    public function testGetContainerProviders(): void
    {
        $providers = new AppGrpcComponentProvider()->getContainerProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(AppGrpcDataServiceProvider::class, $providers[0]);
    }

    public function testGetGrpcProviders(): void
    {
        $providers = new AppGrpcComponentProvider()->getGrpcProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(AppGrpcRouteProvider::class, $providers[0]);
    }

    public function testGetOtherProvidersAreEmpty(): void
    {
        $app      = self::createStub(ApplicationContract::class);
        $provider = new AppGrpcComponentProvider();

        self::assertSame([], $provider->getEventProviders($app));
        self::assertSame([], $provider->getCliProviders($app));
        self::assertSame([], $provider->getHttpProviders($app));
    }
}
