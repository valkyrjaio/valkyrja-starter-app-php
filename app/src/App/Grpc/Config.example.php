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

namespace App\Grpc;

use App\Grpc\Provider\AppGrpcComponentProvider;
use Valkyrja\Application\Data\GrpcConfig;

final class Config extends GrpcConfig
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
            dataPath: 'src/App/Grpc/Data',
            dataNamespace: 'App\\Grpc\\Data',
            port: 50051,
            providers: [
                new AppGrpcComponentProvider(),
            ],
            callbacks: [
                [AppGrpcComponentProvider::class, 'publish'],
            ],
        );
    }
}
