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
