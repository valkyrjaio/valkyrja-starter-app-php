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

namespace App\Grpc\Data;

use Valkyrja\Grpc\Routing\Data\GrpcRoutingData;

final readonly class AppGrpcRoutingData extends GrpcRoutingData
{
    public function __construct()
    {
        parent::__construct(
            routes: [],
        );
    }
}
