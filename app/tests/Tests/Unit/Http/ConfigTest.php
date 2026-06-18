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

namespace App\Tests\Unit\Http;

use App\Http\Config;
use App\Http\Provider\ComponentProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Data\HttpConfig;

final class ConfigTest extends TestCase
{
    public function testIsAnHttpConfig(): void
    {
        self::assertInstanceOf(HttpConfig::class, new Config());
    }

    public function testConfiguredValues(): void
    {
        $config = new Config();

        self::assertSame('App', $config->namespace);
        self::assertSame('1.0.0', $config->version);
        self::assertSame('production', $config->environment);
        self::assertTrue($config->debugMode);
        self::assertSame('UTC', $config->timezone);
        self::assertSame('src/App/Http/Data', $config->dataPath);
        self::assertSame('App\\Http\\Data', $config->dataNamespace);
    }

    public function testRegistersComponentProvider(): void
    {
        self::assertInstanceOf(ComponentProvider::class, new Config()->providers[0]);
    }
}
