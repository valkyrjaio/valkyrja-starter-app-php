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
use Spiral\RoadRunner\Http\HttpWorker;
use Valkyrja\Application\Entry\RoadRunner\RoadRunnerHttp;

use function class_exists;
use function getenv;
use function is_string;
use function method_exists;

/**
 * End-to-end test for the RoadRunner runtime.
 *
 * Runs the real RoadRunner server (`rr serve`), which spawns
 * `app/bin/roadrunner-worker.php`, and asserts a live `GET /` request boots the
 * application, matches the welcome route, and renders its view — exercising the
 * framework's RoadRunner request/response bridge end to end.
 *
 * The RoadRunner server binary is provided explicitly via the RR_BINARY
 * environment variable (the dedicated CI job downloads it and sets it). The test
 * skips when RR_BINARY, the RoadRunner PHP worker package, or the framework
 * bridge is unavailable — so it never runs against the spiral/roadrunner-cli PHP
 * wrapper that shares the `rr` name, and never fails against an incomplete
 * environment.
 */
final class RoadRunnerEntryTest extends RuntimeServerTestCase
{
    private string $binary = 'rr';

    protected function setUp(): void
    {
        $binary = getenv('RR_BINARY');

        if (! is_string($binary) || $binary === '') {
            self::markTestSkipped('Set RR_BINARY to the RoadRunner server binary to run this test.');
        }

        if (! class_exists(HttpWorker::class)) {
            self::markTestSkipped('The spiral/roadrunner-http package is not installed.');
        }

        if (! method_exists(RoadRunnerHttp::class, 'respondToWorker')) {
            self::markTestSkipped('The installed framework predates the RoadRunner response bridge.');
        }

        $this->binary = $binary;
    }

    public function testServesRootRequestOverHttp(): void
    {
        $this->port = $this->findFreePort();
        $rpcPort    = $this->findFreePort();

        $this->startServer([
            $this->binary,
            'serve',
            '-o',
            "http.address=127.0.0.1:{$this->port}",
            '-o',
            "rpc.listen=tcp://127.0.0.1:{$rpcPort}",
        ]);

        $body = $this->httpGet('/');

        // The welcome route rendered the index view (its Valkyrja logo).
        self::assertStringContainsString('full-logo/orange/php.png', $body);
        self::assertStringNotContainsString('404', $body);
        self::assertStringNotContainsString('Fatal error', $body);
        self::assertStringNotContainsString('Uncaught', $body);
    }
}
