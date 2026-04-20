<?php

// phpcs:ignoreFile

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http;

use App\Http\Provider\ComponentProvider;
use Valkyrja\Application\Data\HttpConfig;
use Valkyrja\Application\Provider\HttpApplicationComponentProvider;
use Valkyrja\Http\Server\Middleware\CacheResponseMiddleware;

final class Config extends HttpConfig
{
    public function __construct()
    {
        parent::__construct(
            namespace: 'App',
            dir: __DIR__ . '/../../..',
            version: '1.0.0',
            environment: 'production',
            debugMode: true,
            timezone: 'UTC',
            key: 'some_secret_app_key',
            dataPath: 'App/Http/Data',
            dataNamespace: 'App\\Http\\Data',
            providers: [
                HttpApplicationComponentProvider::class,
                ComponentProvider::class,
            ],
            callbacks: [
                [ComponentProvider::class, 'publish'],
            ],
            requestReceivedMiddleware: [
                CacheResponseMiddleware::class,
            ],
        );
    }
}
