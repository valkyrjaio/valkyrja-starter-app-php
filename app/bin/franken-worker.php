<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App\FrankenPhp\App;
use App\Http\Config;

require_once __DIR__ . '/../../vendor/autoload.php';

App::run(config: new Config());
