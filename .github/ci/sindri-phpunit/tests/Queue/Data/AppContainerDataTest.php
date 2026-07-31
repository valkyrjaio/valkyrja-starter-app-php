<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Queue\Data;

use App\Queue\Data\AppContainerData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Queue\Client\Manager\Contract\ClientContract;
use Valkyrja\Queue\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Queue\Server\Handler\Contract\JobHandlerContract;

/**
 * Assert the sindri-generated queue-component {@see AppContainerData} is correct and populated.
 */
final class AppContainerDataTest extends TestCase
{
    public function testGeneratesPopulatedContainerData(): void
    {
        $data = new AppContainerData();

        self::assertInstanceOf(ContainerData::class, $data);
        self::assertNotEmpty($data->callbacks);
    }

    public function testTheWholeQueuePipelineIsBound(): void
    {
        $data = new AppContainerData();

        // Handing over a queue config brings the whole wiring with it, so the
        // kernel, the router, and the producer must all be in the cache
        self::assertArrayHasKey(JobHandlerContract::class, $data->callbacks);
        self::assertArrayHasKey(RouterContract::class, $data->callbacks);
        self::assertArrayHasKey(ClientContract::class, $data->callbacks);
    }
}
