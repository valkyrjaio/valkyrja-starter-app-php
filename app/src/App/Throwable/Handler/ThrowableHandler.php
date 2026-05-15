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
