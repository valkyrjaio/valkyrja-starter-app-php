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
use App\Queue\Provider\AppQueueComponentProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Queue\Message\Job\Job;

final class ConfigTest extends TestCase
{
    public function testConfiguredValues(): void
    {
        $config = new Config();

        self::assertSame('App', $config->namespace);
        self::assertSame('1.0.0', $config->version);
        self::assertSame('production', $config->environment);
        self::assertTrue($config->debugMode);
        self::assertSame('UTC', $config->timezone);
        self::assertSame('src/App/Queue/Data', $config->dataPath);
        self::assertSame('App\\Queue\\Data', $config->dataNamespace);
        self::assertSame('queue', $config->applicationName);
    }

    public function testInheritsTheFrameworkRetryDefaults(): void
    {
        $config = new Config();

        self::assertSame(Job::DEFAULT_MAX_ATTEMPTS, $config->defaultMaxAttempts);
        self::assertSame(Job::DEFAULT_RETRY_DELAY_MS, $config->defaultRetryDelayMs);
        self::assertFalse($config->defaultRetryDelayMultiplyByAttempt);
    }

    public function testRegistersItsComponentProviderAndPublishCallback(): void
    {
        $config = new Config();

        self::assertInstanceOf(AppQueueComponentProvider::class, $config->providers[0]);
        self::assertSame([AppQueueComponentProvider::class, 'publish'], $config->callbacks[0]);
    }
}
