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

use Override;
use Valkyrja\Application\Data\Contract\QueueConfigContract;
use Valkyrja\Application\Entry\Abstract\InternalQueue;

final class InternalApp extends InternalQueue
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getConfig(): QueueConfigContract
    {
        return new Config();
    }
}
