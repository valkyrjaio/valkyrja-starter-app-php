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

use App\Cli\Command\TestCommand;
use App\Cli\Data\AppContainerData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;

/**
 * Assert the sindri-generated CLI-component {@see AppContainerData} is populated.
 */
final class AppContainerDataTest extends TestCase
{
    public function testGeneratesPopulatedContainerData(): void
    {
        $data = new AppContainerData();

        self::assertInstanceOf(ContainerData::class, $data);

        // The container callbacks are discovered from the app's service providers
        // and must include the application's own CLI command.
        self::assertNotEmpty($data->callbacks);
        self::assertArrayHasKey(TestCommand::class, $data->callbacks);
    }
}
