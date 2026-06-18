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

namespace App\Tests\Unit\Cli\Data;

use App\Cli\Data\AppCliRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\CliRoutingData;

final class AppCliRoutingDataTest extends TestCase
{
    public function testIsACliRoutingData(): void
    {
        self::assertInstanceOf(CliRoutingData::class, new AppCliRoutingData());
    }
}
