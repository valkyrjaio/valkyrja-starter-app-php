<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Grpc\Data;

use App\Grpc\Data\AppContainerData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Grpc\Routing\Dispatcher\Contract\RouterContract;
use Valkyrja\Grpc\Server\Handler\Contract\ServiceHandlerContract;

/**
 * Assert the sindri-generated gRPC-component {@see AppContainerData} is populated.
 */
final class AppContainerDataTest extends TestCase
{
    public function testGeneratesPopulatedContainerData(): void
    {
        $data = new AppContainerData();

        self::assertInstanceOf(ContainerData::class, $data);

        // The container callbacks are discovered from the app's service providers and must include
        // the gRPC stack the component provider pulls in.
        self::assertNotEmpty($data->callbacks);
        self::assertArrayHasKey(ServiceHandlerContract::class, $data->callbacks);
        self::assertArrayHasKey(RouterContract::class, $data->callbacks);
    }
}
