<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Queue;

use App\Throwable\Handler\ThrowableHandler;
use Override;
use Valkyrja\Application\Entry\PushQueue;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;

/**
 * The push consumer, in CGI mode — one job per invocation.
 *
 * A managed processor POSTs the envelope and reads the response status as the
 * settlement, so this rides on the web server already serving the app rather
 * than needing a queue-specific one.
 */
final class PushApp extends PushQueue
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function defaultExceptionHandler(): void
    {
        new ThrowableHandler()->enable(
            displayErrors: true
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getThrowableHandler(): ThrowableHandlerContract
    {
        return new ThrowableHandler();
    }
}
