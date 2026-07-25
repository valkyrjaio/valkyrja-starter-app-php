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

namespace App\Tests\Generated\Cli\Data;

use App\Cli\Data\AppCliRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\CliRoutingData;

/**
 * Assert the sindri-generated CLI-component {@see AppCliRoutingData} is populated.
 *
 * The routes must include the application's own `test` command discovered from
 * its CLI route provider, alongside the framework's built-in commands.
 */
final class AppCliRoutingDataTest extends TestCase
{
    public function testGeneratesPopulatedCliRoutingData(): void
    {
        $data = new AppCliRoutingData();

        self::assertInstanceOf(CliRoutingData::class, $data);

        self::assertNotEmpty($data->routes);
        // The application's own command must be present.
        self::assertArrayHasKey('test', $data->routes);
        // Framework built-in commands are discovered too.
        self::assertArrayHasKey('help', $data->routes);
        self::assertArrayHasKey('version', $data->routes);

        foreach ($data->routes as $routeFactory) {
            self::assertInstanceOf(RouteContract::class, $routeFactory());
        }
    }
}
