<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Cli\Data;

use App\Cli\Data\AppHttpRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

/**
 * Assert the sindri-generated CLI-component {@see AppHttpRoutingData}.
 *
 * The CLI component discovers the same HTTP routes as the HTTP component, so its
 * generated HTTP routing data is fully populated (not an empty stub).
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
        self::assertArrayHasKey('version', $data->routes);
        self::assertArrayHasKey('permutations.num', $data->routes);

        foreach ($data->routes as $routeFactory) {
            self::assertInstanceOf(RouteContract::class, $routeFactory());
        }

        self::assertArrayHasKey('GET', $data->paths);
        self::assertArrayHasKey('POST', $data->paths);
        self::assertArrayHasKey('PUT', $data->paths);
        self::assertArrayHasKey('HEAD', $data->paths);
        self::assertNotEmpty($data->dynamicPaths);
        self::assertNotEmpty($data->regexes);
    }
}
