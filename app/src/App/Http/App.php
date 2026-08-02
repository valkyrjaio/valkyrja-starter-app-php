<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Http;

use App\Throwable\Handler\ThrowableHandler;
use Override;
use Valkyrja\Application\Entry\Http;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;

final class App extends Http
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
