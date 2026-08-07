<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Queue\Provider;

use App\Queue\Job\FlakyJob;
use App\Queue\Job\SendWelcomeEmailJob;
use Override;
use Valkyrja\Queue\Routing\Provider\Contract\QueueRouteProviderContract;

final class AppQueueRouteProvider implements QueueRouteProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        return [
            SendWelcomeEmailJob::class,
            FlakyJob::class,
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
