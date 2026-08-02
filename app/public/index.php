<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

use App\Http\App;
use App\Http\Config;

define('INDEX_START', microtime(true));

require_once __DIR__ . '/../../vendor/autoload.php';

App::run(config: new Config());
