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
 * End-to-end smoke test for the HTTP entry point (`app/public/index.php`).
 *
 * Runs the real front controller in a subprocess and asserts a `GET /` request
 * boots the application, matches the `welcome` route, and renders its view —
 * catching regressions in the entry wiring, provider bootstrap, or routing that
 * class-level tests miss.
 */
final class HttpEntryTest extends TestCase
{
    public function testIndexHandlesRootRequest(): void
    {
        $index = dirname(__DIR__, 5) . '/app/public/index.php';

        $command = 'REQUEST_METHOD=GET REQUEST_URI=/ SCRIPT_NAME=/index.php '
            . escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($index) . ' 2>&1';

        $output   = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        $body = implode("\n", $output);

        self::assertSame(0, $exitCode, "index.php did not run cleanly:\n" . $body);
        // The welcome route rendered the index view (its Valkyrja logo).
        self::assertStringContainsString('full-logo/orange/php.png', $body);
        // It was not the not-found view, and it did not fatal.
        self::assertStringNotContainsString('404', $body);
        self::assertStringNotContainsString('Fatal error', $body);
        self::assertStringNotContainsString('Uncaught', $body);
    }
}
