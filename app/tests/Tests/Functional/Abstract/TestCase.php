<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Functional\Abstract;

use App\Http\App;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;

abstract class TestCase extends PHPUnitTestCase
{
    protected ApplicationContract $app;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        App::directory(__DIR__ . '/../../../..');

        $this->app = App::app(
            config: new Config()
        );
    }
}
