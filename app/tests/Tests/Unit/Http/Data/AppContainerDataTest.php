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

namespace App\Tests\Unit\Http\Data;

use App\Http\Data\AppContainerData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;

final class AppContainerDataTest extends TestCase
{
    public function testIsAContainerData(): void
    {
        self::assertInstanceOf(ContainerData::class, new AppContainerData());
    }
}
