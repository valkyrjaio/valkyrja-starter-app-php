<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

use Valkyrja\Rector\Rules;

return Rules::getConfig()
    ->withAutoloadPaths([
        __DIR__ . '/../../../vendor/autoload.php',
    ])
    ->withPaths([
        __DIR__ . '/../../../app/src',
        __DIR__ . '/../../../app/tests',
    ]);
