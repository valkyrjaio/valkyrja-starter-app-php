<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Grpc;

use App\Throwable\Handler\ThrowableHandler;
use Override;
use Valkyrja\Application\Entry\Abstract\WorkerGrpc;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;

/**
 * The gRPC entry point.
 *
 * gRPC mandates HTTP/2 with trailers, so unlike HTTP there is no in-core server to run: a transport
 * adapter bootstraps this app once and then hands each inbound call to `WorkerGrpc::dispatch()`.
 */
final class App extends WorkerGrpc
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
