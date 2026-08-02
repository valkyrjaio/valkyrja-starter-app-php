<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Cli\Data;

use App\Cli\Data\AppHttpRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class AppHttpRoutingDataTest extends TestCase
{
    public function testIsAHttpRoutingData(): void
    {
        self::assertInstanceOf(HttpRoutingData::class, new AppHttpRoutingData());
    }
}
