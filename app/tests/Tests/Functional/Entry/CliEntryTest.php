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

namespace App\Tests\Functional\Entry;

use PHPUnit\Framework\TestCase;

use function dirname;
use function escapeshellarg;
use function exec;
use function implode;

use const PHP_BINARY;

/**
 * End-to-end smoke test for the CLI entry point (`app/bin/cli`).
 *
 * Runs the real console binary in a subprocess and asserts it boots and lists the
 * application's own `test` command — catching regressions in the entry wiring,
 * provider bootstrap, or command routing that class-level tests miss.
 */
final class CliEntryTest extends TestCase
{
    public function testCliBootsAndListsApplicationCommand(): void
    {
        $cli = dirname(__DIR__, 5) . '/app/bin/cli';

        $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($cli) . ' list 2>&1';

        $output   = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $stdout = implode("\n", $output);

        self::assertSame(0, $exitCode, "app/bin/cli did not boot cleanly:\n" . $stdout);
        // The application's own command is listed.
        self::assertStringContainsString('test', $stdout);
        self::assertStringNotContainsString('Fatal error', $stdout);
        self::assertStringNotContainsString('Uncaught', $stdout);
    }
}
