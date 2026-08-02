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

use App\Http\Controller\RoutingPermutationsController;
use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;

final class RoutingPermutationsControllerTest extends TestCase
{
    private RoutingPermutationsController $controller;

    /**
     * Every parameter-type route echoes the value bound to its parameter.
     *
     * @return array<non-empty-string, array{Closure(RoutingPermutationsController, non-empty-string): TextResponseContract, non-empty-string}>
     */
    public static function parameterTypeProvider(): array
    {
        return [
            'num'                  => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->num($v), '42'],
            'id'                   => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->id($v), '7'],
            'slug'                 => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->slug($v), 'my-slug-1'],
            'alpha'                => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->alpha($v), 'abc'],
            'alpha lowercase'      => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->alphaLowercase($v), 'abc'],
            'alpha uppercase'      => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->alphaUppercase($v), 'ABC'],
            'alpha num'            => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->alphaNum($v), 'abc123'],
            'alpha num underscore' => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->alphaNumUnderscore($v), 'abc_123'],
            'any'                  => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->any($v), 'anything-1.x'],
            'uuid'                 => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->uuid($v), '66a39476-b630-4b95-8bfb-355f3d4843c5'],
            'ulid'                 => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->ulid($v), '01KYGBV64MKWPK63CC1QH0VGF7'],
            'vlid'                 => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->vlid($v), '04YHJMN6F5XHM497ZW'],
            'optional'             => [static fn (RoutingPermutationsController $c, string $v): TextResponseContract => $c->optional($v), 'present'],
        ];
    }

    protected function setUp(): void
    {
        $this->controller = new RoutingPermutationsController(
            self::createStub(ServerRequestContract::class),
            self::createStub(ResponseFactoryContract::class)
        );
    }

    /**
     * @param Closure(RoutingPermutationsController, non-empty-string): TextResponseContract $action
     * @param non-empty-string                                                               $value
     */
    #[DataProvider('parameterTypeProvider')]
    public function testParameterTypeRouteEchoesValue(Closure $action, string $value): void
    {
        $response = $action($this->controller, $value);

        self::assertInstanceOf(TextResponseContract::class, $response);
        self::assertSame($value, (string) $response->getBody());
    }

    public function testMultiCombinesBothParameters(): void
    {
        $response = $this->controller->multi('12', 'two');

        self::assertInstanceOf(TextResponseContract::class, $response);
        self::assertSame('12-two', (string) $response->getBody());
    }

    public function testNonCaptureFallsBackToItsDefault(): void
    {
        $response = $this->controller->nonCapture();

        self::assertInstanceOf(TextResponseContract::class, $response);
        self::assertSame('non-capture', (string) $response->getBody());
    }

    public function testStaticRouteReturnsItsText(): void
    {
        $response = $this->controller->staticRoute();

        self::assertInstanceOf(TextResponseContract::class, $response);
        self::assertSame('static', (string) $response->getBody());
    }

    public function testPostReturnsItsText(): void
    {
        $response = $this->controller->post();

        self::assertInstanceOf(TextResponseContract::class, $response);
        self::assertSame('post', (string) $response->getBody());
    }

    public function testAnyMethodReturnsItsText(): void
    {
        $response = $this->controller->anyMethod();

        self::assertInstanceOf(TextResponseContract::class, $response);
        self::assertSame('any-method', (string) $response->getBody());
    }
}
