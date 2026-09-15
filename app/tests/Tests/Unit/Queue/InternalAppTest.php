<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Queue;

use App\Queue\Config;
use App\Queue\InternalApp;
use PHPUnit\Framework\TestCase;

final class InternalAppTest extends TestCase
{
    public function testTheQueueApplicationRunsAgainstTheQueueConfig(): void
    {
        self::assertInstanceOf(Config::class, InternalApp::getConfig());
    }
}
