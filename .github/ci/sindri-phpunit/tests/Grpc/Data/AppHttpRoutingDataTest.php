<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Grpc\Data;

use App\Grpc\Data\AppHttpRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

/**
 * Assert the sindri-generated gRPC-component {@see AppHttpRoutingData}.
 *
 * The gRPC component contributes no HTTP routes, so its generated HTTP routing data is correctly
 * empty — the generator ran for every protocol and produced a valid, empty map rather than
 * skipping the file.
 */
final class AppHttpRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyHttpRoutingData(): void
    {
        $data = new AppHttpRoutingData();

        self::assertInstanceOf(HttpRoutingData::class, $data);
        self::assertEmpty($data->routes);
        self::assertEmpty($data->paths);
        self::assertEmpty($data->dynamicPaths);
        self::assertEmpty($data->regexes);
    }
}
