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

namespace App\Tests\Unit\Cli\Provider;

use App\Cli\Provider\AppCliComponentProvider;
use App\Cli\Provider\AppCliDataServiceProvider;
use App\Cli\Provider\AppCliRouteProvider;
use App\Cli\Provider\AppCliServiceProvider;
use App\Http\Provider\AppHttpRouteProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\CliWithHttpApplicationComponentProvider;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;

final class AppCliComponentProviderTest extends TestCase
{
    public function testPublishInProductionUsesAppContainerData(): void
    {
        $container = new Container();
        $app       = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);
        $app->method('getDebugMode')->willReturn(false);

        AppCliComponentProvider::publish($app);

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

        AppCliComponentProvider::publish($app);

        self::assertTrue($container->isSingletonInstance(ContainerData::class));
    }

    public function testGetComponentProviders(): void
    {
        $providers = new AppCliComponentProvider()->getComponentProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(CliWithHttpApplicationComponentProvider::class, $providers[0]);
    }

    public function testGetContainerProviders(): void
    {
        $providers = new AppCliComponentProvider()->getContainerProviders(self::createStub(ApplicationContract::class));

        self::assertInstanceOf(AppCliDataServiceProvider::class, $providers[0]);
        self::assertInstanceOf(AppCliServiceProvider::class, $providers[1]);
    }

    public function testGetEventProviders(): void
    {
        self::assertSame([], new AppCliComponentProvider()->getEventProviders(self::createStub(ApplicationContract::class)));
    }

    public function testGetCliProviders(): void
    {
        $providers = new AppCliComponentProvider()->getCliProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(AppCliRouteProvider::class, $providers[0]);
    }

    public function testGetHttpProviders(): void
    {
        $providers = new AppCliComponentProvider()->getHttpProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(AppHttpRouteProvider::class, $providers[0]);
    }
}
