<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

use Arkitect\CLI\Config;
use Valkyrja\Arkitect\Rules;

return static function (Config $config): void {
    $srcDir   = __DIR__ . '/../../../app/src';
    $testsDir = __DIR__ . '/../../../app/tests';

    Rules::getRules(srcDir: $srcDir, testsDir: $testsDir)($config);
};
