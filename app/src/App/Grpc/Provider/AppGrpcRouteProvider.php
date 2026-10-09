<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Grpc\Provider;

use App\Grpc\Controller\GreeterController;
use Override;
use Valkyrja\Grpc\Routing\Provider\Contract\GrpcRouteProviderContract;

final class AppGrpcRouteProvider implements GrpcRouteProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        return [
            GreeterController::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRoutes(): array
    {
        return [];
    }
}
