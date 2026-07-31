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

use App\Cli\Data\AppQueueRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Queue\Routing\Data\QueueRoutingData;

/**
 * Assert the sindri-generated CLI-component {@see AppQueueRoutingData}.
 *
 * The CLI component declares no queue route provider, so its generated
 * queue routing data is empty. That emptiness is the assertion: a config must
 * not pick up another component's jobs just because generation runs for all
 * five data classes.
 */
final class AppQueueRoutingDataTest extends TestCase
{
    public function testGeneratesEmptyQueueRoutingData(): void
    {
        $data = new AppQueueRoutingData();

        self::assertInstanceOf(QueueRoutingData::class, $data);
        self::assertSame([], $data->routes);
    }
}
