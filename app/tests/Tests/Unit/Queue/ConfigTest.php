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
use Valkyrja\Queue\Client\Data\Contract\QueueRedisClientConfigContract;

use function getenv;
use function putenv;

final class ConfigTest extends TestCase
{
    /** @var non-empty-string[] */
    private const array REDIS_VARIABLES = [
        'QUEUE_REDIS_HOST',
        'QUEUE_REDIS_PORT',
        'QUEUE_REDIS_PREFIX',
        'QUEUE_NAME',
        'QUEUE_WORKER_NAME',
    ];

    /** @var array<non-empty-string, string|false> */
    private array $originalEnvironment = [];

    protected function setUp(): void
    {
        foreach (self::REDIS_VARIABLES as $variable) {
            $this->originalEnvironment[$variable] = getenv($variable);

            putenv($variable);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->originalEnvironment as $variable => $value) {
            if ($value === false) {
                putenv($variable);

                continue;
            }

            putenv("$variable=$value");
        }
    }

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

    public function testTheWorkerConfigNamesTheRedisServerItself(): void
    {
        // The provider binds the application config straight through when it
        // carries this contract, and falls back to a localhost default when it
        // does not, so the queue would silently read the wrong server
        self::assertInstanceOf(QueueRedisClientConfigContract::class, new Config());
    }

    public function testRedisSettingsFallBackWhenTheEnvironmentIsUnset(): void
    {
        $config = new Config();

        self::assertSame('127.0.0.1', $config->queueRedisClientHost);
        self::assertSame(6379, $config->queueRedisClientPort);
        self::assertSame('', $config->queueRedisClientPrefix);
        self::assertSame('queues:default', $config->queueRedisClientQueue);
        self::assertSame('default', $config->queueRedisClientWorkerName);
    }

    public function testRedisSettingsComeFromTheEnvironment(): void
    {
        putenv('QUEUE_REDIS_HOST=redis.example.com');
        putenv('QUEUE_REDIS_PORT=6380');
        putenv('QUEUE_REDIS_PREFIX=app:');
        putenv('QUEUE_NAME=app:queue');
        putenv('QUEUE_WORKER_NAME=worker-7');

        $config = new Config();

        self::assertSame('redis.example.com', $config->queueRedisClientHost);
        self::assertSame(6380, $config->queueRedisClientPort);
        self::assertSame('app:', $config->queueRedisClientPrefix);
        self::assertSame('app:queue', $config->queueRedisClientQueue);
        self::assertSame('worker-7', $config->queueRedisClientWorkerName);
    }

    public function testAnEmptyVariableTakesTheFallbackRatherThanAnEmptyName(): void
    {
        foreach (self::REDIS_VARIABLES as $variable) {
            putenv("$variable=");
        }

        $config = new Config();

        // A name the contract declares non-empty must never come back empty
        self::assertSame('127.0.0.1', $config->queueRedisClientHost);
        self::assertSame(6379, $config->queueRedisClientPort);
        self::assertSame('queues:default', $config->queueRedisClientQueue);
        self::assertSame('default', $config->queueRedisClientWorkerName);
        // A prefix is the one setting an empty value legitimately names
        self::assertSame('', $config->queueRedisClientPrefix);
    }

    public function testRegistersItsComponentProviderAndPublishCallback(): void
    {
        $config = new Config();

        self::assertInstanceOf(AppQueueComponentProvider::class, $config->providers[0]);
        self::assertSame([AppQueueComponentProvider::class, 'publish'], $config->callbacks[0]);
    }
}
