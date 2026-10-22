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

use App\Queue\Data\AppCliRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\CliRoutingData;

final class AppCliRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyCliRoutingData(): void
    {
        $data = new AppCliRoutingData();

        self::assertInstanceOf(CliRoutingData::class, $data);
        self::assertSame([], $data->routes);
    }
}
