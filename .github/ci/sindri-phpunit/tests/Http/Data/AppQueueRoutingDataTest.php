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

use App\Http\Data\AppQueueRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Queue\Routing\Data\QueueRoutingData;

final class AppQueueRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyQueueRoutingData(): void
    {
        $data = new AppQueueRoutingData();

        self::assertInstanceOf(QueueRoutingData::class, $data);
        self::assertSame([], $data->routes);
    }
}
