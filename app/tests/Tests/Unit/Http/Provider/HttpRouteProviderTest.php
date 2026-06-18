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

use App\Http\Controller\HomeController;
use App\Http\Provider\HttpRouteProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;

final class HttpRouteProviderTest extends TestCase
{
    private Container $container;

    private RouteContract $route;

    protected function setUp(): void
    {
        $responseFactory = self::createStub(ResponseFactoryContract::class);
        $responseFactory->method('createTextResponse')->willReturn(self::createStub(TextResponseContract::class));
        $responseFactory->method('createJsonResponse')->willReturn(self::createStub(JsonResponseContract::class));

        $viewFactory = self::createStub(ViewResponseFactoryContract::class);
        $viewFactory->method('createResponseFromView')->willReturn(self::createStub(ResponseContract::class));

        $app = self::createStub(ApplicationContract::class);
        $app->method('getVersion')->willReturn('1.0.0');

        $this->container = new Container();
        $this->container->setSingleton(ApplicationContract::class, $app);
        $this->container->setSingleton(ResponseFactoryContract::class, $responseFactory);
        $this->container->setSingleton(ViewResponseFactoryContract::class, $viewFactory);
        $this->container->setSingleton(
            HomeController::class,
            new HomeController(self::createStub(ServerRequestContract::class), $responseFactory)
        );

        $this->route = self::createStub(RouteContract::class);
    }

    public function testVersionHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, HttpRouteProvider::versionHandler($this->container, $this->route));
    }

    public function testTextHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, HttpRouteProvider::textHandler($this->container, $this->route));
    }

    public function testWelcomeHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, HttpRouteProvider::welcomeHandler($this->container, $this->route));
    }

    public function testWelcomeCachedHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, HttpRouteProvider::welcomeCachedHandler($this->container, $this->route));
    }

    public function testDynamicHandler(): void
    {
        $parameter = self::createStub(ParameterContract::class);
        $parameter->method('getValue')->willReturn('abc');

        $route = self::createStub(DynamicRouteContract::class);
        $route->method('getParameter')->willReturn($parameter);

        self::assertInstanceOf(ResponseContract::class, HttpRouteProvider::dynamicHandler($this->container, $route));
    }

    public function testHomeHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, HttpRouteProvider::homeHandler($this->container, $this->route));
    }

    public function testJsonHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, HttpRouteProvider::jsonHandler($this->container, $this->route));
    }

    public function testGetControllerClasses(): void
    {
        self::assertSame([HomeController::class], new HttpRouteProvider()->getControllerClasses());
    }

    public function testGetRoutes(): void
    {
        self::assertSame([], new HttpRouteProvider()->getRoutes());
    }
}
