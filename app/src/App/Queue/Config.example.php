<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

// phpcs:ignoreFile

namespace App\Queue;

use App\Queue\Provider\AppQueueComponentProvider;
use Valkyrja\Application\Data\QueueConfig;
use Valkyrja\Queue\Client\Data\Contract\QueueRedisClientConfigContract;

use function getenv;

final class Config extends QueueConfig implements QueueRedisClientConfigContract
{
    /** @var non-empty-string */
    public string $queueRedisClientHost {
        get {
            $host = getenv('QUEUE_REDIS_HOST');

            return $host !== false && $host !== ''
                ? $host
                : '127.0.0.1';
        }
    }

    public int $queueRedisClientPort {
        get {
            $port = getenv('QUEUE_REDIS_PORT');

            return $port !== false && $port !== ''
                ? (int) $port
                : 6379;
        }
    }

    public string $queueRedisClientPrefix {
        get {
            $prefix = getenv('QUEUE_REDIS_PREFIX');

            return $prefix !== false
                ? $prefix
                : '';
        }
    }

    /** @var non-empty-string */
    public string $queueRedisClientQueue {
        get {
            $queue = getenv('QUEUE_NAME');

            return $queue !== false && $queue !== ''
                ? $queue
                : 'queues:default';
        }
    }

    /**
     * Every worker on one queue needs its own slot. Two that share a name share
     * an in-flight list, so a restart of one redelivers the other's running job.
     *
     * @var non-empty-string
     */
    public string $queueRedisClientWorkerName {
        get {
            $name = getenv('QUEUE_WORKER_NAME');

            return $name !== false && $name !== ''
                ? $name
                : 'default';
        }
    }

    public function __construct()
    {
        parent::__construct(
            namespace: 'App',
            dir: __DIR__ . '/../../..',
            version: '1.0.0',
            environment: 'production',
            debugMode: true,
            timezone: 'UTC',
            key: 'some_secret_app_key',
            dataPath: 'src/App/Queue/Data',
            dataNamespace: 'App\\Queue\\Data',
            applicationName: 'queue',
            providers: [
                new AppQueueComponentProvider(),
            ],
            callbacks: [
                [AppQueueComponentProvider::class, 'publish'],
            ],
        );
    }
}
