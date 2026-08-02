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
