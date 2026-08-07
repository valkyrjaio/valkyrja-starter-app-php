<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

use App\Queue\Config;
use App\Queue\PushApp;

define('INDEX_START', microtime(true));

require_once __DIR__ . '/../../vendor/autoload.php';

// The push front controller. A managed processor (Cloud Tasks, Pub/Sub push,
// SQS-to-HTTPS, any webhook broker) POSTs the envelope here and reads the
// response status as the settlement — so the queue rides on the web server the
// app already runs rather than needing one of its own.
PushApp::run(config: new Config());
