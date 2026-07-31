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
use Valkyrja\Application\Entry\PullQueue;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;

/**
 * The default consumer: a plain long-running poll loop, run under whatever
 * process manager you already use.
 *
 * The runtime is in the class name, not the directory — the app groups its
 * entries by protocol, so a per-runtime push worker sits beside this as
 * OpenSwoolePushApp rather than nesting under a runtime segment.
 */
final class App extends PullQueue
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
