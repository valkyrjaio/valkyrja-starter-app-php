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

        // Nine routes on HomeController plus the eighteen routing permutations.
        self::assertCount(27, $data->routes);
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

    /**
     * Every routing permutation is generated with the exact regex the framework's
     * processor produces, so the cached routing table matches runtime matching.
     */
    public function testGeneratesExpectedRegexForEveryPermutation(): void
    {
        $data = new AppHttpRoutingData();

        $expected = [
            'permutations.num'                => '/^\/permutations\/num\/(?<value>\d+)$/',
            'permutations.id'                 => '/^\/permutations\/id\/(?<value>\d+)$/',
            'permutations.slug'               => '/^\/permutations\/slug\/(?<value>[a-zA-Z0-9-]+)$/',
            'permutations.alpha'              => '/^\/permutations\/alpha\/(?<value>[a-zA-Z]+)$/',
            'permutations.alphaLowercase'     => '/^\/permutations\/alpha-lowercase\/(?<value>[a-z]+)$/',
            'permutations.alphaUppercase'     => '/^\/permutations\/alpha-uppercase\/(?<value>[A-Z]+)$/',
            'permutations.alphaNum'           => '/^\/permutations\/alpha-num\/(?<value>[a-zA-Z0-9]+)$/',
            'permutations.alphaNumUnderscore' => '/^\/permutations\/alpha-num-underscore\/(?<value>\w+)$/',
            'permutations.any'                => '/^\/permutations\/any\/(?<value>.*)$/',
            'permutations.multi'              => '/^\/permutations\/multi\/(?<first>\d+)\/(?<second>[a-zA-Z]+)$/',
            // A non-capturing parameter produces a group without a name.
            'permutations.nonCapture'         => '/^\/permutations\/non-capture\/(?:[a-zA-Z]+)$/',
            // An optional parameter makes the preceding slash optional too.
            'permutations.optional'           => '/^\/permutations\/optional(?:\/)?(?<value>[a-zA-Z]+)?$/',
        ];

        foreach ($expected as $name => $regex) {
            self::assertArrayHasKey($name, $data->routes, "Route '$name' was not generated");
            self::assertArrayHasKey($regex, $data->regexes['GET'], "Regex for '$name' was not generated");
            self::assertSame($name, $data->regexes['GET'][$regex]);
        }

        // The static permutations are registered as paths rather than regexes.
        self::assertArrayHasKey('/permutations/static', $data->paths['GET']);
        self::assertSame('permutations.static', $data->paths['GET']['/permutations/static']);

        // A method-restricted route is only registered under that method.
        self::assertArrayHasKey('/permutations/post', $data->paths['POST']);
        self::assertArrayNotHasKey('/permutations/post', $data->paths['GET']);

        // A route declared for any method is registered under every method.
        self::assertArrayHasKey('/permutations/any-method', $data->paths['GET']);
        self::assertArrayHasKey('/permutations/any-method', $data->paths['POST']);
        self::assertArrayHasKey('/permutations/any-method', $data->paths['DELETE']);
    }
}
