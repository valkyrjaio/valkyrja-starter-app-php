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

use App\Http\Data\AppHttpRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class AppHttpRoutingDataTest extends TestCase
{
    public function testIsAHttpRoutingData(): void
    {
        self::assertInstanceOf(HttpRoutingData::class, new AppHttpRoutingData());
    }
}
