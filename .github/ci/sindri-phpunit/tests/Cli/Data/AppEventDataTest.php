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

namespace App\Tests\Generated\Cli\Data;

use App\Cli\Data\AppEventData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Event\Data\EventData;

/**
 * Assert the sindri-generated CLI-component {@see AppEventData}.
 *
 * The application registers no events or listeners, so the generated event data
 * is correctly empty — the generator ran and produced valid, empty collections.
 */
final class AppEventDataTest extends TestCase
{
    public function testGeneratesEmptyEventData(): void
    {
        $data = new AppEventData();

        self::assertInstanceOf(EventData::class, $data);
        self::assertEmpty($data->events);
        self::assertEmpty($data->listeners);
    }
}
