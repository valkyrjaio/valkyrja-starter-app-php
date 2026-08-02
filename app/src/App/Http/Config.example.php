<?php

// phpcs:ignoreFile

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Http;

use App\Http\Provider\AppHttpComponentProvider;
use Valkyrja\Application\Data\HttpConfig;
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
            dataPath: 'src/App/Http/Data',
            dataNamespace: 'App\\Http\\Data',
            providers: [
                new AppHttpComponentProvider(),
            ],
            callbacks: [
                [AppHttpComponentProvider::class, 'publish'],
            ],
            requestReceivedMiddleware: [
                CacheResponseMiddleware::class,
            ],
        );
    }
}
