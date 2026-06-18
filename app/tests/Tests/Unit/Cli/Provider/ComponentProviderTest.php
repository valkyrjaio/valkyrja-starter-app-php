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

use App\Cli\Provider\CliRouteProvider;
use App\Cli\Provider\ComponentProvider;
use App\Cli\Provider\DataServiceProvider;
use App\Cli\Provider\ServiceProvider;
use App\Http\Provider\HttpRouteProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\CliWithHttpApplicationComponentProvider;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;

final class ComponentProviderTest extends TestCase
{
    public function testPublishInProductionUsesAppContainerData(): void
    {
        $container = new Container();
        $app       = self::createStub(ApplicationContract::class);
        $app->method('getContainer')->willReturn($container);
        $app->method('getDebugMode')->willReturn(false);

        ComponentProvider::publish($app);

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

        ComponentProvider::publish($app);

        self::assertTrue($container->isSingletonInstance(ContainerData::class));
    }

    public function testGetComponentProviders(): void
    {
        $providers = new ComponentProvider()->getComponentProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(CliWithHttpApplicationComponentProvider::class, $providers[0]);
    }

    public function testGetContainerProviders(): void
    {
        $providers = new ComponentProvider()->getContainerProviders(self::createStub(ApplicationContract::class));

        self::assertInstanceOf(DataServiceProvider::class, $providers[0]);
        self::assertInstanceOf(ServiceProvider::class, $providers[1]);
    }

    public function testGetEventProviders(): void
    {
        self::assertSame([], new ComponentProvider()->getEventProviders(self::createStub(ApplicationContract::class)));
    }

    public function testGetCliProviders(): void
    {
        $providers = new ComponentProvider()->getCliProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(CliRouteProvider::class, $providers[0]);
    }

    public function testGetHttpProviders(): void
    {
        $providers = new ComponentProvider()->getHttpProviders(self::createStub(ApplicationContract::class));

        self::assertCount(1, $providers);
        self::assertInstanceOf(HttpRouteProvider::class, $providers[0]);
    }
}
