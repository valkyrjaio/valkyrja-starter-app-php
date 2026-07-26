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

namespace App\Tests\Generated\Http\Data;

use App\Http\Data\AppHttpRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

/**
 * Assert the sindri-generated {@see AppHttpRoutingData} is correct and populated.
 *
 * Unlike the stub-based phpunit suite (which copies the empty *.example.php stubs
 * and only asserts the class is an HttpRoutingData), this suite runs against the
 * real classes emitted by `sindri data:generate` and asserts the application's
 * routes are actually present.
 */
final class AppHttpRoutingDataTest extends TestCase
{
    public function testGeneratesPopulatedRoutingData(): void
    {
        $data = new AppHttpRoutingData();

        self::assertInstanceOf(HttpRoutingData::class, $data);

        // The application defines nine HTTP routes across its controllers/providers.
        self::assertCount(9, $data->routes);
        self::assertArrayHasKey('welcome', $data->routes);
        self::assertArrayHasKey('welcome.cached', $data->routes);
        self::assertArrayHasKey('version', $data->routes);
        self::assertArrayHasKey('dynamicValue', $data->routes);

        // Every route entry is a lazy factory that resolves to a route.
        foreach ($data->routes as $routeFactory) {
            self::assertInstanceOf(RouteContract::class, $routeFactory());
        }

        // The HTTP method -> path maps are built for each method the app uses.
        self::assertArrayHasKey('GET', $data->paths);
        self::assertArrayHasKey('POST', $data->paths);
        self::assertArrayHasKey('PUT', $data->paths);
        self::assertArrayHasKey('HEAD', $data->paths);
        self::assertArrayHasKey('/', $data->paths['GET']);

        // The dynamic /{value} route contributes dynamic paths and their regexes.
        self::assertArrayHasKey('GET', $data->dynamicPaths);
        self::assertArrayHasKey('HEAD', $data->dynamicPaths);
        self::assertNotEmpty($data->regexes);
    }
}
