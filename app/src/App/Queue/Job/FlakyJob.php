<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Queue\Job;

use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Queue\Message\Enum\JobResult;
use Valkyrja\Queue\Routing\Attribute\Route;
use Valkyrja\Queue\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Queue\Routing\Data\Contract\RouteContract;

final class FlakyJob
{
    /** @var non-empty-string */
    public const string NAME = 'Flaky';

    public static function handle(ContainerContract $container, RouteContract $route): JobResult
    {
        return JobResult::RETRY;
    }

    #[Route(name: self::NAME, description: 'Always asks to be retried, to demonstrate the retry chain')]
    #[RouteHandler([self::class, 'handle'])]
    public function flake(): JobResult
    {
        return JobResult::RETRY;
    }
}
