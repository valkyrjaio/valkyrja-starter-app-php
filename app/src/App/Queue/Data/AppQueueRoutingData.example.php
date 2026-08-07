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

namespace App\Queue\Data;

use Valkyrja\Queue\Routing\Data\QueueRoutingData;

final readonly class AppQueueRoutingData extends QueueRoutingData
{
    public function __construct()
    {
        parent::__construct(
            routes: [],
        );
    }
}
