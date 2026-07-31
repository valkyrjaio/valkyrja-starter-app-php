<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Grpc\Provider;

use App\Grpc\Controller\GreeterController;
use App\Grpc\Provider\AppGrpcRouteProvider;
use PHPUnit\Framework\TestCase;

final class AppGrpcRouteProviderTest extends TestCase
{
    public function testGetControllerClasses(): void
    {
        self::assertSame([GreeterController::class], new AppGrpcRouteProvider()->getControllerClasses());
    }

    public function testGetRoutes(): void
    {
        self::assertSame([], new AppGrpcRouteProvider()->getRoutes());
    }
}
