<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Http\Provider;

use App\Http\Controller\HomeController;
use App\Http\Controller\RoutingPermutationsController;
use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Provider\Contract\HttpRouteProviderContract;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;

final class AppHttpRouteProvider implements HttpRouteProviderContract
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

    public static function permutationsNumHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->num(self::permutationValue($route, 'value'));
    }

    public static function permutationsIdHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->id(self::permutationValue($route, 'value'));
    }

    public static function permutationsSlugHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->slug(self::permutationValue($route, 'value'));
    }

    public static function permutationsAlphaHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->alpha(self::permutationValue($route, 'value'));
    }

    public static function permutationsAlphaLowercaseHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->alphaLowercase(self::permutationValue($route, 'value'));
    }

    public static function permutationsAlphaUppercaseHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->alphaUppercase(self::permutationValue($route, 'value'));
    }

    public static function permutationsAlphaNumHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->alphaNum(self::permutationValue($route, 'value'));
    }

    public static function permutationsAlphaNumUnderscoreHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->alphaNumUnderscore(self::permutationValue($route, 'value'));
    }

    public static function permutationsAnyHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->any(self::permutationValue($route, 'value'));
    }

    public static function permutationsUuidHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->uuid(self::permutationValue($route, 'value'));
    }

    public static function permutationsUlidHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->ulid(self::permutationValue($route, 'value'));
    }

    public static function permutationsVlidHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->vlid(self::permutationValue($route, 'value'));
    }

    public static function permutationsOptionalHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->optional(self::permutationValue($route, 'value'));
    }

    public static function permutationsMultiHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->multi(
            self::permutationValue($route, 'first'),
            self::permutationValue($route, 'second')
        );
    }

    public static function permutationsNonCaptureHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        // The parameter is matched but not captured, so the controller default is used.
        return self::permutationsController($container)->nonCapture();
    }

    public static function permutationsStaticHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->staticRoute();
    }

    public static function permutationsPostHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->post();
    }

    public static function permutationsAnyMethodHandler(ContainerContract $container, RouteContract $route): ResponseContract
    {
        return self::permutationsController($container)->anyMethod();
    }

    /**
     * Get the routing permutations controller.
     */
    private static function permutationsController(ContainerContract $container): RoutingPermutationsController
    {
        return $container->getSingleton(RoutingPermutationsController::class);
    }

    /**
     * Get a bound parameter's value from a dynamic route.
     *
     * @param non-empty-string $name The parameter name
     */
    private static function permutationValue(RouteContract $route, string $name): string
    {
        /**
         * @var DynamicRouteContract $route
         * @var string               $value
         */
        $value = $route->getParameter($name)->getValue();

        return $value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        return [
            HomeController::class,
            RoutingPermutationsController::class,
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
