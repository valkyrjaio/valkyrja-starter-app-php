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

use App\Queue\Data\AppHttpRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class AppHttpRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyHttpRoutingData(): void
    {
        $data = new AppHttpRoutingData();

        self::assertInstanceOf(HttpRoutingData::class, $data);
        self::assertSame([], $data->routes);
    }
}
