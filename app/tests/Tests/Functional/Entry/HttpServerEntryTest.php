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

use const PHP_BINARY;

/**
 * End-to-end test for the standard HTTP runtime.
 *
 * Serves `app/public` through PHP's built-in web server and asserts a real
 * `GET /` HTTP request boots the application, matches the welcome route, and
 * renders its view — exercising the full front-controller path over a socket.
 */
final class HttpServerEntryTest extends RuntimeServerTestCase
{
    public function testServesRootRequestOverHttp(): void
    {
        $this->port = $this->findFreePort();

        $this->startServer([
            PHP_BINARY,
            '-S',
            "127.0.0.1:{$this->port}",
            '-t',
            'app/public',
        ]);

        $body = $this->httpGet('/');

        // The welcome route rendered the index view (its Valkyrja logo).
        self::assertStringContainsString('full-logo/orange/php.png', $body);
        self::assertStringNotContainsString('404', $body);
        self::assertStringNotContainsString('Fatal error', $body);
        self::assertStringNotContainsString('Uncaught', $body);
    }
}
