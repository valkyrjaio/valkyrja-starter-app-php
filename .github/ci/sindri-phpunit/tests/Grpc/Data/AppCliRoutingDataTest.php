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

use App\Grpc\Data\AppCliRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\CliRoutingData;

/**
 * Assert the sindri-generated gRPC-component {@see AppCliRoutingData}.
 *
 * The gRPC component contributes no CLI commands, so its generated CLI routing data is correctly
 * empty — the generator ran for every protocol and produced a valid, empty map rather than
 * skipping the file.
 */
final class AppCliRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyCliRoutingData(): void
    {
        $data = new AppCliRoutingData();

        self::assertInstanceOf(CliRoutingData::class, $data);
        self::assertEmpty($data->routes);
    }
}
