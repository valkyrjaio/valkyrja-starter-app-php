<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Grpc;

use App\Grpc\Config;
use App\Grpc\Provider\AppGrpcComponentProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Data\Contract\GrpcConfigContract;

final class ConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(GrpcConfigContract::class, new Config());
    }

    public function testValues(): void
    {
        $config = new Config();

        self::assertSame('App', $config->namespace);
        self::assertSame('1.0.0', $config->version);
        self::assertSame('production', $config->environment);
        self::assertTrue($config->debugMode);
        self::assertSame('UTC', $config->timezone);
        self::assertSame(50051, $config->port);
        self::assertSame('src/App/Grpc/Data', $config->dataPath);
        self::assertSame('App\\Grpc\\Data', $config->dataNamespace);
    }

    public function testProvidersAndCallbacks(): void
    {
        $config = new Config();

        self::assertCount(1, $config->providers);
        self::assertInstanceOf(AppGrpcComponentProvider::class, $config->providers[0]);
        self::assertSame([[AppGrpcComponentProvider::class, 'publish']], $config->callbacks);
    }
}
