<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Cli;

use App\Cli\Config;
use App\Cli\Provider\AppCliComponentProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Data\CliConfig;

final class ConfigTest extends TestCase
{
    public function testIsACliConfig(): void
    {
        self::assertInstanceOf(CliConfig::class, new Config());
    }

    public function testConfiguredValues(): void
    {
        $config = new Config();

        self::assertSame('App', $config->namespace);
        self::assertSame('1.0.0', $config->version);
        self::assertSame('production', $config->environment);
        self::assertTrue($config->debugMode);
        self::assertSame('UTC', $config->timezone);
        self::assertSame('src/App/Cli/Data', $config->dataPath);
        self::assertSame('App\\Cli\\Data', $config->dataNamespace);
    }

    public function testRegistersComponentProvider(): void
    {
        self::assertInstanceOf(AppCliComponentProvider::class, new Config()->providers[0]);
    }
}
