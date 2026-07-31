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

use App\Queue\Provider\AppQueueComponentProvider;
use App\Queue\Provider\AppQueueDataServiceProvider;
use App\Queue\Provider\AppQueueRouteProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\QueueApplicationComponentProvider;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;

final class AppQueueComponentProviderTest extends TestCase
{
    public function testPublishInProductionUsesAppContainerData(): void
    {
        $container = new Container();
        $app       = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);
        $app->method('getDebugMode')->willReturn(false);

        AppQueueComponentProvider::publish($app);

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

        AppQueueComponentProvider::publish($app);

        self::assertTrue($container->isSingletonInstance(ContainerData::class));
    }

    public function testGetComponentProviders(): void
    {
        $providers = new AppQueueComponentProvider()->getComponentProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(QueueApplicationComponentProvider::class, $providers[0]);
    }

    public function testGetContainerProviders(): void
    {
        $providers = new AppQueueComponentProvider()->getContainerProviders(self::createStub(ApplicationContract::class));

        self::assertInstanceOf(AppQueueDataServiceProvider::class, $providers[0]);
    }

    public function testGetEventProviders(): void
    {
        self::assertSame([], new AppQueueComponentProvider()->getEventProviders(self::createStub(ApplicationContract::class)));
    }

    public function testGetCliProviders(): void
    {
        self::assertSame([], new AppQueueComponentProvider()->getCliProviders(self::createStub(ApplicationContract::class)));
    }

    public function testGetHttpProviders(): void
    {
        // A queue-only app loads no Http stack — the dependency is one-way
        self::assertSame([], new AppQueueComponentProvider()->getHttpProviders(self::createStub(ApplicationContract::class)));
    }

    public function testGetQueueProviders(): void
    {
        $providers = new AppQueueComponentProvider()->getQueueProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(AppQueueRouteProvider::class, $providers[0]);
    }
}
