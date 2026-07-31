<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Queue\Provider;

use App\Queue\Job\FlakyJob;
use App\Queue\Job\SendWelcomeEmailJob;
use App\Queue\Provider\AppQueueRouteProvider;
use PHPUnit\Framework\TestCase;

final class AppQueueRouteProviderTest extends TestCase
{
    public function testGetControllerClasses(): void
    {
        self::assertSame(
            [SendWelcomeEmailJob::class, FlakyJob::class],
            new AppQueueRouteProvider()->getControllerClasses()
        );
    }

    public function testGetRoutes(): void
    {
        // The app declares its jobs by attribute, so nothing is registered manually
        self::assertSame([], new AppQueueRouteProvider()->getRoutes());
    }
}
