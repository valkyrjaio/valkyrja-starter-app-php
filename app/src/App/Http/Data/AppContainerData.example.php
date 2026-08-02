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

namespace App\Http\Data;

use Valkyrja\Container\Data\ContainerData;

final readonly class AppContainerData extends ContainerData
{
    public function __construct()
    {
        parent::__construct(
            aliases: [],
        );
    }
}
