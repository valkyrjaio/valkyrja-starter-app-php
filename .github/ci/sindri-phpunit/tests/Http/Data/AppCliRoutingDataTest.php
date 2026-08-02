<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Http\Data;

use App\Http\Data\AppCliRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

/**
 * Assert the sindri-generated HTTP-component {@see AppCliRoutingData}.
 *
 * The HTTP component contributes the framework's route-listing CLI command, so
 * its generated CLI routing data is populated (not an empty stub).
 */
final class AppCliRoutingDataTest extends TestCase
{
    public function testGeneratesPopulatedCliRoutingData(): void
    {
        $data = new AppCliRoutingData();

        self::assertInstanceOf(CliRoutingData::class, $data);

        self::assertNotEmpty($data->routes);

        foreach ($data->routes as $routeFactory) {
            self::assertInstanceOf(RouteContract::class, $routeFactory());
        }
    }
}
