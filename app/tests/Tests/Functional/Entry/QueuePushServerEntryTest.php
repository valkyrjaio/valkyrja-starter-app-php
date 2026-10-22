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

use App\Queue\Job\FlakyJob;
use App\Queue\Job\SendWelcomeEmailJob;
use App\Tests\Functional\Abstract\RuntimeServerTestCase;
use Valkyrja\Queue\Message\Job\Factory\JobFactory;
use Valkyrja\Queue\Message\Job\Job;

use function json_encode;

use const PHP_BINARY;

final class QueuePushServerEntryTest extends RuntimeServerTestCase
{
    public function testAnAcknowledgedJobSettlesWithATwoHundred(): void
    {
        $this->startQueueServer();

        $response = $this->push(new JobFactory()->create(SendWelcomeEmailJob::NAME, ['user_id' => 42]));

        // 2xx tells the processor to delete the message
        self::assertSame(204, $response['status']);
        self::assertStringNotContainsString('Fatal error', $response['body']);
        self::assertStringNotContainsString('Uncaught', $response['body']);
    }

    public function testAMalformedPayloadSettlesAsTerminalRatherThanRetryable(): void
    {
        $this->startQueueServer();

        $response = $this->push(new JobFactory()->create(SendWelcomeEmailJob::NAME, ['user_id' => 'not-an-int']));

        // The handler gave up on purpose, so the processor must not redeliver
        self::assertSame(422, $response['status']);
    }

    public function testAnUnknownJobNameSettlesAsTerminal(): void
    {
        $this->startQueueServer();

        $response = $this->push(new JobFactory()->create('NoSuchJob'));

        // There is no handler to retry into, so redelivery would never help
        self::assertSame(422, $response['status']);
    }

    public function testARetryableJobBelowItsCeilingAsksForRedelivery(): void
    {
        $this->startQueueServer();

        // FlakyJob always asks to be retried, so the attempt budget alone
        // decides between redelivery and a terminal settlement
        $response = $this->push(new Job(
            name: FlakyJob::NAME,
            attempts: 1,
            maxAttempts: 5,
        ));

        self::assertSame(503, $response['status']);
    }

    public function testAnExhaustedRetryChainSettlesAsTerminal(): void
    {
        $this->startQueueServer();

        // The same always-retryable job at its ceiling, so a 422 here can only
        // come from the budget running out
        $response = $this->push(new Job(
            name: FlakyJob::NAME,
            attempts: 5,
            maxAttempts: 5,
        ));

        self::assertSame(422, $response['status']);
    }

    /**
     * Serve the application's public directory, which carries the push front controller.
     */
    private function startQueueServer(): void
    {
        $this->port = $this->findFreePort();

        $this->startServer([
            PHP_BINARY,
            '-S',
            "127.0.0.1:{$this->port}",
            '-t',
            'app/public',
        ]);
    }

    /**
     * POST a job's wire envelope at the push front controller.
     *
     * @return array{status: int, body: string}
     */
    private function push(Job $job): array
    {
        return $this->pushEnvelope($job->asArray());
    }

    /**
     * POST a raw wire envelope at the push front controller.
     *
     * @param array<non-empty-string, mixed> $envelope The envelope
     *
     * @return array{status: int, body: string}
     */
    private function pushEnvelope(array $envelope): array
    {
        return $this->httpSend('/queue.php', (string) json_encode($envelope));
    }
}
