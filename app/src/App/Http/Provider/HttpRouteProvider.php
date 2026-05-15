<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Provider;

use App\Http\Controller\HomeController;
use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Provider\Contract\HttpRouteProviderContract;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;

final class HttpRouteProvider implements HttpRouteProviderContract
{
    public static function versionHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return HomeController::version(
            $container->getSingleton(ApplicationContract::class),
            $container->getSingleton(ResponseFactoryContract::class),
        );
    }

    public static function textHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return HomeController::text();
    }

    public static function welcomeHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return $container->getSingleton(HomeController::class)->welcome(
            $container->getSingleton(ViewResponseFactoryContract::class),
        );
    }

    public static function welcomeCachedHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return $container->getSingleton(HomeController::class)->welcomeCached(
            $container->getSingleton(ViewResponseFactoryContract::class),
        );
    }

    public static function dynamicHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        /**
         * @var DynamicRouteContract $route
         * @var string               $value
         */
        $value = $route->getParameter('value')->getValue();

        return $container->getSingleton(HomeController::class)->dynamic(
            $route,
            $container->getSingleton(ViewResponseFactoryContract::class),
            $value
        );
    }

    public static function homeHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return $container->getSingleton(HomeController::class)->home(
            $container->getSingleton(ViewResponseFactoryContract::class),
        );
    }

    public static function jsonHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return $container->getSingleton(HomeController::class)->json(
            $container->getSingleton(ResponseFactoryContract::class),
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        return [
            HomeController::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRoutes(): array
    {
        return [];
    }
}
