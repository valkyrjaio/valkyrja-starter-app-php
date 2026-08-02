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

use App\Tests\Functional\Abstract\RuntimeServerTestCase;

use function getenv;
use function is_string;

/**
 * End-to-end test for the FrankenPHP runtime.
 *
 * Runs the real FrankenPHP worker server, which boots
 * `app/bin/franken-worker.php`, and asserts a live `GET /` request boots the
 * application, matches the welcome route, and renders its view — exercising the
 * FrankenPHP worker entry end to end.
 *
 * The FrankenPHP binary is provided explicitly via the FRANKENPHP_BINARY
 * environment variable (the dedicated CI job installs it and sets it). The test
 * skips when it is unset, so it never fails against an environment that lacks the
 * runtime.
 */
final class FrankenPhpEntryTest extends RuntimeServerTestCase
{
    private string $binary = 'frankenphp';

    protected function setUp(): void
    {
        $binary = getenv('FRANKENPHP_BINARY');

        if (! is_string($binary) || $binary === '') {
            self::markTestSkipped('Set FRANKENPHP_BINARY to the frankenphp binary to run this test.');
        }

        $this->binary = $binary;
    }

    public function testServesRootRequestOverHttp(): void
    {
        $this->port = $this->findFreePort();

        $worker = $this->appRoot() . '/app/bin/franken-worker.php';

        $this->startServer([
            $this->binary,
            'php-server',
            '--listen',
            "127.0.0.1:{$this->port}",
            '--root',
            'app/public',
            '--worker',
            $worker,
        ]);

        $body = $this->httpGet('/');

        // The welcome route rendered the index view (its Valkyrja logo).
        self::assertStringContainsString('full-logo/orange/php.png', $body);
        self::assertStringNotContainsString('404', $body);
        self::assertStringNotContainsString('Fatal error', $body);
        self::assertStringNotContainsString('Uncaught', $body);
    }
}
