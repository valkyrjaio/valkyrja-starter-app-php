<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Http\Controller;

use App\Http\Controller\HomeController;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;

final class HomeControllerTest extends TestCase
{
    private ResponseFactoryContract $responseFactory;

    private ViewResponseFactoryContract $viewFactory;

    private HomeController $controller;

    protected function setUp(): void
    {
        $this->responseFactory = self::createStub(ResponseFactoryContract::class);
        $this->responseFactory->method('createTextResponse')->willReturn(self::createStub(TextResponseContract::class));
        $this->responseFactory->method('createJsonResponse')->willReturn(self::createStub(JsonResponseContract::class));

        $this->viewFactory = self::createStub(ViewResponseFactoryContract::class);
        $this->viewFactory->method('createResponseFromView')->willReturn(self::createStub(ResponseContract::class));

        $this->controller = new HomeController(self::createStub(ServerRequestContract::class), $this->responseFactory);
    }

    public function testVersionReturnsTextResponse(): void
    {
        $app = self::createStub(ApplicationContract::class);
        $app->method('getVersion')->willReturn('1.0.0');

        self::assertInstanceOf(TextResponseContract::class, HomeController::version($app, $this->responseFactory));
    }

    public function testTextReturnsTextResponse(): void
    {
        self::assertInstanceOf(TextResponseContract::class, HomeController::text());
    }

    public function testWelcomeReturnsResponse(): void
    {
        self::assertInstanceOf(ResponseContract::class, $this->controller->welcome($this->viewFactory));
    }

    public function testWelcomeCachedReturnsResponse(): void
    {
        self::assertInstanceOf(ResponseContract::class, $this->controller->welcomeCached($this->viewFactory));
    }

    public function testDynamicReturnsResponse(): void
    {
        self::assertInstanceOf(
            ResponseContract::class,
            $this->controller->dynamic(self::createStub(RouteContract::class), $this->viewFactory, 'abc'),
        );
    }

    public function testHomeReturnsResponse(): void
    {
        self::assertInstanceOf(ResponseContract::class, $this->controller->home($this->viewFactory));
    }

    public function testJsonReturnsJsonResponse(): void
    {
        self::assertInstanceOf(JsonResponseContract::class, $this->controller->json($this->responseFactory));
    }
}
