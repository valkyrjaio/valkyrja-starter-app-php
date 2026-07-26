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

use App\Http\Data\AppCliRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\CliRoutingData;

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
