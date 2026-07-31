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

namespace App\Queue;

use App\Queue\Provider\AppQueueComponentProvider;
use Valkyrja\Application\Data\QueueConfig;

final class Config extends QueueConfig
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
            dataPath: 'src/App/Queue/Data',
            dataNamespace: 'App\\Queue\\Data',
            applicationName: 'queue',
            providers: [
                new AppQueueComponentProvider(),
            ],
            callbacks: [
                [AppQueueComponentProvider::class, 'publish'],
            ],
        );
    }
}
