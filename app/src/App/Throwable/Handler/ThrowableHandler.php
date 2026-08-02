<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Throwable\Handler;

use Override;
use Valkyrja\Throwable\Handler\WhoopsThrowableHandler;

use const E_ALL;

class ThrowableHandler extends WhoopsThrowableHandler
{
    /**
     * Enable debug mode.
     *
     * @param int  $errorReportingLevel [optional] The error reporting level
     * @param bool $displayErrors       [optional] Whether to display errors
     */
    #[Override]
    public function enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false): void
    {
    }
}
