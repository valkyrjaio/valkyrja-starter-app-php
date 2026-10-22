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

final class QueueEntryTest extends TestCase
{
    public function testQueueBootsAndRunsTheApplicationJob(): void
    {
        $stdout   = '';
        $exitCode = $this->runQueue('42', $stdout);

        self::assertSame(0, $exitCode, "app/bin/queue-sync did not boot cleanly:\n" . $stdout);
        // The application's own job ran, on its first and only attempt
        self::assertStringContainsString('SendWelcomeEmail attempt=1', $stdout);
        self::assertStringNotContainsString('Fatal error', $stdout);
        self::assertStringNotContainsString('Uncaught', $stdout);
    }

    public function testAJobIdIsStamped(): void
    {
        $stdout = '';
        $this->runQueue('7', $stdout);

        // The id is producer-generated and stable across retries
        self::assertMatchesRegularExpression('/id=[0-9A-Z]{26}/', $stdout);
    }

    /**
     * Run the queue binary in a subprocess.
     */
    private function runQueue(string $userId, string &$stdout): int
    {
        $bin = dirname(__DIR__, 5) . '/app/bin/queue-sync';

        $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($bin) . ' ' . escapeshellarg($userId) . ' 2>&1';

        $output   = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $stdout = implode("\n", $output);

        return $exitCode;
    }
}
