<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Queue\Data;

use App\Queue\Data\AppGrpcRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Grpc\Routing\Data\GrpcRoutingData;

final class AppGrpcRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyGrpcRoutingData(): void
    {
        $data = new AppGrpcRoutingData();

        self::assertInstanceOf(GrpcRoutingData::class, $data);
        self::assertSame([], $data->routes);
    }
}
