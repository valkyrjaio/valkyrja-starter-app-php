<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Unit\Http\Provider;

use App\Http\Provider\AppHttpComponentProvider;
use App\Http\Provider\AppHttpDataServiceProvider;
use App\Http\Provider\AppHttpRouteProvider;
use App\Http\Provider\AppHttpServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\HttpApplicationComponentProvider;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;

final class AppHttpComponentProviderTest extends TestCase
{
    public function testPublishInProductionUsesAppContainerData(): void
    {
        $container = new Container();
        $app       = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);
        $app->method('getDebugMode')->willReturn(false);

        AppHttpComponentProvider::publish($app);

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

        AppHttpComponentProvider::publish($app);

        self::assertTrue($container->isSingletonInstance(ContainerData::class));
    }

    public function testGetComponentProviders(): void
    {
        $providers = new AppHttpComponentProvider()->getComponentProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(HttpApplicationComponentProvider::class, $providers[0]);
    }

    public function testGetContainerProviders(): void
    {
        $providers = new AppHttpComponentProvider()->getContainerProviders(self::createStub(ApplicationContract::class));

        self::assertInstanceOf(AppHttpDataServiceProvider::class, $providers[0]);
        self::assertInstanceOf(AppHttpServiceProvider::class, $providers[1]);
    }

    public function testGetEventProviders(): void
    {
        self::assertSame([], new AppHttpComponentProvider()->getEventProviders(self::createStub(ApplicationContract::class)));
    }

    public function testGetCliProviders(): void
    {
        self::assertSame([], new AppHttpComponentProvider()->getCliProviders(self::createStub(ApplicationContract::class)));
    }

    public function testGetHttpProviders(): void
    {
        $providers = new AppHttpComponentProvider()->getHttpProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(AppHttpRouteProvider::class, $providers[0]);
    }
}
