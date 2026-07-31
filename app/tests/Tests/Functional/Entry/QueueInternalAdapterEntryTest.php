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

use PHPUnit\Framework\TestCase;

use function dirname;
use function escapeshellarg;
use function exec;
use function implode;

use const PHP_BINARY;

/**
 * End-to-end tests for the three in-process queue adapters.
 *
 * Each runs the real `app/bin/queue-internal` binary in a subprocess, so a job
 * is published and consumed inside a genuinely booted application rather than a
 * test harness.
 *
 * These adapters are a *testing* shape by design: in a real deployment an app
 * publishes to a broker and a separate worker consumes. An app self-consuming
 * its own jobs is realistically only wanted in a test or a zero-infrastructure
 * dev environment — which is exactly why they are worth pinning down, since
 * that is where they will actually be used.
 *
 * Consumption is asserted through the retry chain rather than a side effect: a
 * retry hands an incremented job back to the client, so the handover count is
 * external proof the job ran and settled rather than merely being enqueued.
 */
final class QueueInternalAdapterEntryTest extends TestCase
{
    public function testSyncRunsTheJobImmediately(): void
    {
        $output = $this->runAdapter('sync', 'ack');

        self::assertStringContainsString('handled=1', $output);
    }

    public function testSyncRunsAWholeRetryChainToItsCeiling(): void
    {
        $output = $this->runAdapter('sync', 'retry');

        // Three handovers: the original push plus two retries, after which the
        // ceiling turns the last retry into a dead-letter
        self::assertStringContainsString('handled=3', $output);
    }

    public function testDeferredHoldsTheJobUntilItIsDrained(): void
    {
        $output = $this->runAdapter('deferred', 'ack');

        // Nothing has run at push time — that is the whole point of deferring
        self::assertStringContainsString('buffered=1 handled=0', $output);
        self::assertStringContainsString('handled=1', $output);
    }

    public function testDeferredDrainsAWholeRetryChain(): void
    {
        $output = $this->runAdapter('deferred', 'retry');

        // Once the response is out there is no later moment to finish in, so
        // the drain keeps going until the chain terminates
        self::assertStringContainsString('handled=3', $output);
    }

    public function testInMemoryHoldsTheJobUntilItIsDrained(): void
    {
        $output = $this->runAdapter('inmemory', 'ack');

        self::assertStringContainsString('buffered=1 handled=0', $output);
        self::assertStringContainsString('handled=1', $output);
    }

    public function testInMemoryReBuffersARetryForTheTestToDrainAgain(): void
    {
        $output = $this->runAdapter('inmemory', 'retry');

        // Distinct from sync and deferred: in-memory hands the retry back to
        // the buffer and stops, leaving the test in control of re-draining
        self::assertStringContainsString('handled=2', $output);
    }

    /**
     * Run the internal-adapter binary and return its output.
     *
     * @param non-empty-string $adapter The adapter to exercise
     * @param non-empty-string $mode    Whether the job acknowledges or retries
     */
    private function runAdapter(string $adapter, string $mode): string
    {
        $bin = dirname(__DIR__, 5) . '/app/bin/queue-internal';

        $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($bin)
            . ' ' . escapeshellarg($adapter) . ' ' . escapeshellarg($mode) . ' 2>&1';

        $output   = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $stdout = implode("\n", $output);

        self::assertSame(0, $exitCode, "app/bin/queue-internal $adapter $mode failed:\n" . $stdout);
        self::assertStringNotContainsString('Fatal error', $stdout);
        self::assertStringNotContainsString('Uncaught', $stdout);

        return $stdout;
    }
}
