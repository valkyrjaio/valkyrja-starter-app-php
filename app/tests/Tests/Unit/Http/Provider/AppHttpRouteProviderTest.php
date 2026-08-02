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
use App\Http\Controller\RoutingPermutationsController;
use App\Http\Provider\AppHttpRouteProvider;
use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Routing\Data\Contract\DynamicRouteContract;
use Valkyrja\Http\Routing\Data\Contract\ParameterContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\View\Factory\Contract\ViewResponseFactoryContract;

final class AppHttpRouteProviderTest extends TestCase
{
    private Container $container;

    private RouteContract $route;

    /**
     * Every single-parameter permutation handler binds the route's value and echoes it.
     *
     * @return array<non-empty-string, array{Closure(ContainerContract, RouteContract): ResponseContract, non-empty-string}>
     */
    public static function permutationHandlerProvider(): array
    {
        return [
            'num'                  => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsNumHandler($c, $r), '42'],
            'id'                   => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsIdHandler($c, $r), '7'],
            'slug'                 => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsSlugHandler($c, $r), 'my-slug-1'],
            'alpha'                => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsAlphaHandler($c, $r), 'abc'],
            'alpha lowercase'      => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsAlphaLowercaseHandler($c, $r), 'abc'],
            'alpha uppercase'      => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsAlphaUppercaseHandler($c, $r), 'ABC'],
            'alpha num'            => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsAlphaNumHandler($c, $r), 'abc123'],
            'alpha num underscore' => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsAlphaNumUnderscoreHandler($c, $r), 'abc_123'],
            'any'                  => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsAnyHandler($c, $r), 'anything-1.x'],
            'uuid'                 => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsUuidHandler($c, $r), '66a39476-b630-4b95-8bfb-355f3d4843c5'],
            'ulid'                 => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsUlidHandler($c, $r), '01KYGBV64MKWPK63CC1QH0VGF7'],
            'vlid'                 => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsVlidHandler($c, $r), '04YHJMN6F5XHM497ZW'],
            'optional'             => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsOptionalHandler($c, $r), 'present'],
        ];
    }

    /**
     * Every parameterless permutation handler returns its fixed text.
     *
     * @return array<non-empty-string, array{Closure(ContainerContract, RouteContract): ResponseContract, non-empty-string}>
     */
    public static function staticPermutationHandlerProvider(): array
    {
        return [
            'non capture' => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsNonCaptureHandler($c, $r), 'non-capture'],
            'static'      => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsStaticHandler($c, $r), 'static'],
            'post'        => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsPostHandler($c, $r), 'post'],
            'any method'  => [static fn (ContainerContract $c, RouteContract $r): ResponseContract => AppHttpRouteProvider::permutationsAnyMethodHandler($c, $r), 'any-method'],
        ];
    }

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
        $this->container->setSingleton(
            RoutingPermutationsController::class,
            new RoutingPermutationsController(self::createStub(ServerRequestContract::class), $responseFactory)
        );

        $this->route = self::createStub(RouteContract::class);
    }

    public function testVersionHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, AppHttpRouteProvider::versionHandler($this->container, $this->route));
    }

    public function testTextHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, AppHttpRouteProvider::textHandler($this->container, $this->route));
    }

    public function testWelcomeHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, AppHttpRouteProvider::welcomeHandler($this->container, $this->route));
    }

    public function testWelcomeCachedHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, AppHttpRouteProvider::welcomeCachedHandler($this->container, $this->route));
    }

    public function testDynamicHandler(): void
    {
        $parameter = self::createStub(ParameterContract::class);
        $parameter->method('getValue')->willReturn('abc');

        $route = self::createStub(DynamicRouteContract::class);
        $route->method('getParameter')->willReturn($parameter);

        self::assertInstanceOf(ResponseContract::class, AppHttpRouteProvider::dynamicHandler($this->container, $route));
    }

    public function testHomeHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, AppHttpRouteProvider::homeHandler($this->container, $this->route));
    }

    public function testJsonHandler(): void
    {
        self::assertInstanceOf(ResponseContract::class, AppHttpRouteProvider::jsonHandler($this->container, $this->route));
    }

    /**
     * @param Closure(ContainerContract, RouteContract): ResponseContract $handler
     * @param non-empty-string                                            $value
     */
    #[DataProvider('permutationHandlerProvider')]
    public function testPermutationHandlerEchoesBoundValue(Closure $handler, string $value): void
    {
        $response = $handler($this->container, $this->dynamicRouteWithValues(['value' => $value]));

        self::assertInstanceOf(ResponseContract::class, $response);
        self::assertSame($value, (string) $response->getBody());
    }

    /**
     * @param Closure(ContainerContract, RouteContract): ResponseContract $handler
     * @param non-empty-string                                            $expected
     */
    #[DataProvider('staticPermutationHandlerProvider')]
    public function testStaticPermutationHandlerReturnsItsText(Closure $handler, string $expected): void
    {
        $response = $handler($this->container, $this->route);

        self::assertInstanceOf(ResponseContract::class, $response);
        self::assertSame($expected, (string) $response->getBody());
    }

    public function testPermutationsMultiHandlerBindsBothParameters(): void
    {
        $route = $this->dynamicRouteWithValues(['first' => '12', 'second' => 'two']);

        $response = AppHttpRouteProvider::permutationsMultiHandler($this->container, $route);

        self::assertInstanceOf(ResponseContract::class, $response);
        self::assertSame('12-two', (string) $response->getBody());
    }

    public function testGetControllerClasses(): void
    {
        self::assertSame(
            [HomeController::class, RoutingPermutationsController::class],
            new AppHttpRouteProvider()->getControllerClasses()
        );
    }

    public function testGetRoutes(): void
    {
        self::assertSame([], new AppHttpRouteProvider()->getRoutes());
    }

    /**
     * Build a dynamic route whose parameters resolve to the given name => value pairs.
     *
     * @param array<non-empty-string, non-empty-string> $values
     */
    private function dynamicRouteWithValues(array $values): DynamicRouteContract
    {
        $route = self::createStub(DynamicRouteContract::class);
        $route->method('getParameter')
            ->willReturnCallback(
                static function (string $name) use ($values): ParameterContract {
                    $parameter = self::createStub(ParameterContract::class);
                    $parameter->method('getValue')->willReturn($values[$name] ?? null);

                    return $parameter;
                }
            );

        return $route;
    }
}
