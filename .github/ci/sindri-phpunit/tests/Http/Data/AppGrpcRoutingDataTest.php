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

use App\Http\Data\AppGrpcRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Grpc\Routing\Data\GrpcRoutingData;

/**
 * Assert the sindri-generated Http-component {@see AppGrpcRoutingData}.
 *
 * The Http component contributes no gRPC services, so its generated gRPC routing data is
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
