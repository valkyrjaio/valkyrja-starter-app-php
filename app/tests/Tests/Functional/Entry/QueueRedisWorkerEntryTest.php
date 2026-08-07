<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Functional\Entry;

use App\Queue\Job\SendWelcomeEmailJob;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Valkyrja\Queue\Client\Manager\RedisClient;
use Valkyrja\Queue\Message\Job\Factory\JobFactory;

use function class_exists;
use function dirname;
use function escapeshellarg;
use function exec;
use function getenv;
use function implode;
use function is_string;

use const PHP_BINARY;

final class QueueRedisWorkerEntryTest extends TestCase
{
    /** @var non-empty-string */
    private const string QUEUE = 'app:tests:queue';

    private Client $redis;

    private string $dsn = '';

    protected function setUp(): void
    {
        $dsn = getenv('QUEUE_REDIS_DSN');

        if (! is_string($dsn) || $dsn === '') {
            self::markTestSkipped('Set QUEUE_REDIS_DSN to a reachable Redis server to run this test.');
        }

        if (! class_exists(Client::class)) {
            self::markTestSkipped('The predis/predis package is not installed.');
        }

        $this->dsn   = $dsn;
        $this->redis = new Client($dsn);
        $this->redis->connect();

        $this->flush();
    }

    protected function tearDown(): void
    {
        if (isset($this->redis)) {
            $this->flush();
            $this->redis->disconnect();
        }
    }

    public function testTheWorkerConsumesAJobFromRedis(): void
    {
        $client = new RedisClient(redis: $this->redis, queue: self::QUEUE);
        $client->push(new JobFactory()->create(SendWelcomeEmailJob::NAME, ['user_id' => 42]));

        self::assertSame(1, (int) $this->redis->llen(self::QUEUE));

        $stdout   = '';
        $exitCode = $this->runWorker($stdout);

        self::assertSame(0, $exitCode, "app/bin/queue did not run cleanly:\n" . $stdout);
        // Acknowledged means consumed: nothing is left for another worker
        self::assertSame(0, (int) $this->redis->llen(self::QUEUE));
        self::assertStringNotContainsString('Fatal error', $stdout);
        self::assertStringNotContainsString('Uncaught', $stdout);
    }

    public function testTheWorkerExitsOnItsJobBoundWithAnEmptyQueue(): void
    {
        // No job to take, so the time bound is what ends the loop — proving the
        // blocking pop yields rather than hanging the process forever
        $stdout   = '';
        $exitCode = $this->runWorker($stdout, maxSeconds: 1);

        self::assertSame(0, $exitCode, "app/bin/queue did not exit cleanly:\n" . $stdout);
    }

    public function testAMalformedPayloadIsNotReturnedToTheQueue(): void
    {
        $client = new RedisClient(redis: $this->redis, queue: self::QUEUE);
        $client->push(new JobFactory()->create(SendWelcomeEmailJob::NAME, ['user_id' => 'not-an-int']));

        $stdout = '';
        $this->runWorker($stdout);

        // The handler gave up on purpose, so nothing is re-enqueued or held
        self::assertSame(0, (int) $this->redis->llen(self::QUEUE));
        self::assertSame(0, (int) $this->redis->zcard(self::QUEUE . RedisClient::DELAYED_SUFFIX));
    }

    /**
     * Run the real worker binary with a bounded lifetime.
     */
    private function runWorker(string &$stdout, int $maxJobs = 1, int $maxSeconds = 10): int
    {
        $bin = dirname(__DIR__, 5) . '/app/bin/queue';

        $command = 'QUEUE_REDIS_DSN=' . escapeshellarg($this->dsn)
            . ' QUEUE_NAME=' . escapeshellarg(self::QUEUE)
            . ' QUEUE_MAX_JOBS=' . escapeshellarg((string) $maxJobs)
            . ' QUEUE_MAX_SECONDS=' . escapeshellarg((string) $maxSeconds)
            . ' ' . escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($bin) . ' 2>&1';

        $output   = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $stdout = implode("\n", $output);

        return $exitCode;
    }

    private function flush(): void
    {
        $this->redis->del([self::QUEUE, self::QUEUE . RedisClient::DELAYED_SUFFIX]);
    }
}
