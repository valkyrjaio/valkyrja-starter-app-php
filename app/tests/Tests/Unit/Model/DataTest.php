<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
