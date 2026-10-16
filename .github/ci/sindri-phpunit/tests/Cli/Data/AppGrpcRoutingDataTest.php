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

use App\Cli\Data\AppGrpcRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Grpc\Routing\Data\GrpcRoutingData;

/**
 * Assert the sindri-generated Cli-component {@see AppGrpcRoutingData}.
 *
 * The Cli component contributes no gRPC services, so its generated gRPC routing data is
 * correctly empty — the generator ran for every protocol and produced a valid, empty service map
 * rather than skipping the file.
 */
final class AppGrpcRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyGrpcRoutingData(): void
    {
        $data = new AppGrpcRoutingData();

        self::assertInstanceOf(GrpcRoutingData::class, $data);
        self::assertEmpty($data->routes);
    }
}
