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

use App\Queue\Data\AppEventData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Event\Data\EventData;

/**
 * Assert the sindri-generated queue-component {@see AppEventData}.
 *
 * The queue component declares no listeners, so the generated event data is
 * empty — the assertion is that generation still emits a valid, loadable class
 * rather than skipping it.
 */
final class AppEventDataTest extends TestCase
{
    public function testGeneratesLoadableEventData(): void
    {
        $data = new AppEventData();

        self::assertInstanceOf(EventData::class, $data);
        self::assertSame([], $data->listeners);
    }
}
