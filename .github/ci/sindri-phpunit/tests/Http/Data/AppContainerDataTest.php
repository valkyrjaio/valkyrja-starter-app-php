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

namespace App\Tests\Generated\Http\Data;

use App\Http\Controller\HomeController;
use App\Http\Data\AppContainerData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;

/**
 * Assert the sindri-generated {@see AppContainerData} is correct and populated.
 */
final class AppContainerDataTest extends TestCase
{
    public function testGeneratesPopulatedContainerData(): void
    {
        $data = new AppContainerData();

        self::assertInstanceOf(ContainerData::class, $data);

        // The container callbacks are discovered from the app's service providers
        // and must include the application's own HTTP controller.
        self::assertNotEmpty($data->callbacks);
        self::assertArrayHasKey(HomeController::class, $data->callbacks);
    }
}
