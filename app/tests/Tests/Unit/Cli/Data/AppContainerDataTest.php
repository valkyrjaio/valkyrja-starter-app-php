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

use App\Cli\Data\AppContainerData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;

final class AppContainerDataTest extends TestCase
{
    public function testIsAContainerData(): void
    {
        self::assertInstanceOf(ContainerData::class, new AppContainerData());
    }
}
