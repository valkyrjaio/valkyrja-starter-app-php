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
use OpenSwoole\Http\Server;
use Override;
use Valkyrja\Application\Entry\OpenSwoole\OpenSwooleHttp;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;

use function getenv;

final class OpenSwooleApp extends OpenSwooleHttp
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

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getSwooleServer(): Server
    {
        // Read from the environment so a caller can bind a chosen address.
        $host = getenv('APP_OPENSWOOLE_HOST');
        $port = getenv('APP_OPENSWOOLE_PORT');

        return new Server(
            $host !== false ? $host : '127.0.0.1',
            $port !== false ? (int) $port : 9501
        );
    }
}
