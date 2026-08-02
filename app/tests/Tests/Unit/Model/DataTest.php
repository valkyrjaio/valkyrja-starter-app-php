<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Model;

use App\Model\Data;
use PHPUnit\Framework\TestCase;

final class DataTest extends TestCase
{
    public function testDefaultPropertyValues(): void
    {
        $data = new Data();

        self::assertSame('hello', $data->property);
        self::assertNull($data->propertyNullable);
    }
}
