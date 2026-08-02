<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Http\Controller;

use App\Http\Controller\Abstract\Controller;
use App\Http\Provider\AppHttpRouteProvider;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Routing\Attribute\Parameter;
use Valkyrja\Http\Routing\Attribute\Route;
use Valkyrja\Http\Routing\Attribute\Route\Middleware;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Get;
use Valkyrja\Http\Routing\Attribute\Route\RequestMethod\Head;
use Valkyrja\Http\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Http\Routing\Constant\Regex;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;

class HomeController extends Controller
{
    /**
     * Application version action.
     */
    #[Route(path: '/version', name: 'version', requestMethods: [RequestMethod::GET])]
    #[Route(path: '/version', name: 'version.post', requestMethods: [RequestMethod::POST])]
    #[Route(path: '/version', name: 'version.put', requestMethods: [RequestMethod::PUT])]
    #[RouteHandler([AppHttpRouteProvider::class, 'versionHandler'])]
    public static function version(ApplicationContract $app, ResponseFactoryContract $responseFactory): TextResponseContract
    {
        return $responseFactory->createTextResponse($app->getVersion());
    }

    /**
     * Text action.
     */
    #[Route(path: '/text', name: 'text', requestMethods: [RequestMethod::GET])]
    #[RouteHandler([AppHttpRouteProvider::class, 'textHandler'])]
    public static function text(): TextResponseContract
    {
        return new TextResponse('Hello World!');
    }

    /**
     * Welcome action.
     * - Example of a view being returned.
     */
    #[Route(path: '/', name: 'welcome')]
    #[RouteHandler([AppHttpRouteProvider::class, 'welcomeHandler'])]
    public function welcome(ViewResponseFactoryContract $responseFactory): ResponseContract
    {
        return $responseFactory->createResponseFromView('index/index');
    }

    /**
     * Welcome cached action.
     * - Example of a cacheable view being returned.
     */
    #[Route(path: '/cached', name: 'welcome.cached')]
    #[RouteHandler([AppHttpRouteProvider::class, 'welcomeCachedHandler'])]
    #[Middleware(CacheResponseMiddleware::class)]
    public function welcomeCached(ViewResponseFactoryContract $responseFactory): ResponseContract
    {
        return $responseFactory->createResponseFromView('index/index');
    }

    /**
     * Dynamic action.
     * - Example of a view being returned.
     */
    #[Route(path: '/{value}', name: 'dynamicValue')]
    #[RouteHandler([AppHttpRouteProvider::class, 'dynamicHandler'])]
    public function dynamic(
        RouteContract $route,
        ViewResponseFactoryContract $responseFactory,
        #[Parameter(name: 'value', regex: Regex::ALPHA)]
        string $value
    ): ResponseContract {
        return $responseFactory->createResponseFromView('dynamic/dynamic', ['value' => $value]);
    }

    /**
     * Home action.
     */
    #[Get]
    #[Head]
    #[Route(path: '/home', name: 'home')]
    #[RouteHandler([AppHttpRouteProvider::class, 'homeHandler'])]
    public function home(ViewResponseFactoryContract $responseFactory): ResponseContract
    {
        return $responseFactory->createResponseFromView('home/home');
    }

    /**
     * Json action.
     */
    #[Route(path: '/json', name: 'json')]
    #[RouteHandler([AppHttpRouteProvider::class, 'jsonHandler'])]
    public function json(ResponseFactoryContract $responseFactory): JsonResponseContract
    {
        return $responseFactory->createJsonResponse(['Json response example']);
    }
}
