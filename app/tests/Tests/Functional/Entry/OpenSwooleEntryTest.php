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

use App\Tests\Functional\Abstract\RuntimeServerTestCase;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;

use function extension_loaded;
use function method_exists;

use const PHP_BINARY;

/**
 * End-to-end test for the OpenSwoole runtime.
 *
 * Boots `app/bin/openswoole` as a real OpenSwoole HTTP server and asserts a live
 * `GET /` request boots the application, matches the welcome route, and renders
 * its view — exercising the OpenSwoole request/response bridge end to end.
 *
 * Skipped when the openswoole extension is unavailable or the installed
 * framework predates the request/response bridge (so it never fails against an
 * older release that cannot serve a live OpenSwoole request).
 */
final class OpenSwooleEntryTest extends RuntimeServerTestCase
{
    protected function setUp(): void
    {
        if (! extension_loaded('openswoole')) {
            self::markTestSkipped('The openswoole extension is not loaded.');
        }

        if (! method_exists(OpenSwooleHttp::class, 'handleSwooleRequest')) {
            self::markTestSkipped('The installed framework predates the OpenSwoole request/response bridge.');
        }
    }

    public function testServesRootRequestOverHttp(): void
    {
        $this->port = $this->findFreePort();

        $this->startServer(
            [PHP_BINARY, 'app/bin/openswoole'],
            [
                'APP_OPENSWOOLE_HOST' => '127.0.0.1',
                'APP_OPENSWOOLE_PORT' => (string) $this->port,
            ]
        );

        $body = $this->httpGet('/');

        // The welcome route rendered the index view (its Valkyrja logo).
        self::assertStringContainsString('full-logo/orange/php.png', $body);
        self::assertStringNotContainsString('404', $body);
        self::assertStringNotContainsString('Fatal error', $body);
        self::assertStringNotContainsString('Uncaught', $body);
    }
}
