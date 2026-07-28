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
     *
     * Host and port are read from the environment so the server can be bound to
     * a chosen address (e.g. a free port during end-to-end tests).
     */
    #[Override]
    public static function getSwooleServer(): Server
    {
        $host = getenv('APP_OPENSWOOLE_HOST');
        $port = getenv('APP_OPENSWOOLE_PORT');

        return new Server(
            $host !== false ? $host : '127.0.0.1',
            $port !== false ? (int) $port : 9501
        );
    }
}
