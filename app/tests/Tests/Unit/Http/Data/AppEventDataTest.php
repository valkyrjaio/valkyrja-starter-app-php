<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Http\Data;

use App\Http\Data\AppEventData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Event\Data\EventData;

final class AppEventDataTest extends TestCase
{
    public function testIsAEventData(): void
    {
        self::assertInstanceOf(EventData::class, new AppEventData());
    }
}
