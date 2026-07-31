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

use Valkyrja\Event\Data\EventData;

final readonly class AppEventData extends EventData
{
    public function __construct()
    {
        parent::__construct(
            events: [],
            listeners: [],
        );
    }
}
