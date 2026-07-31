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
use Valkyrja\Queue\Message\Job\Contract\JobContract;
use Valkyrja\Queue\Routing\Attribute\Route;
use Valkyrja\Queue\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Queue\Routing\Data\Contract\RouteContract;

use function is_int;

/**
 * A job handler.
 *
 * The handler only ever sees the job — settlement is framework plumbing, so
 * nothing here knows or cares whether the job arrived from an in-process push,
 * a Redis pop, or an HTTP push from a managed processor.
 *
 * Duplicate delivery is expected under at-least-once semantics: the attempt
 * count and the stable id are exposed precisely so a handler can be idempotent,
 * but only the handler can decide what that means for its own side effects.
 */
final class SendWelcomeEmailJob
{
    /** @var non-empty-string */
    public const string NAME = 'SendWelcomeEmail';

    public static function handle(ContainerContract $container, RouteContract $route): JobResult
    {
        $job = $container->getSingleton(JobContract::class);

        // A malformed payload fails rather than retries: redelivering it would
        // reproduce the same failure until the attempt budget ran out
        return is_int($job->getPayload()->get('user_id'))
            ? JobResult::ACK
            : JobResult::FAIL;
    }

    #[Route(name: self::NAME, description: 'Send a new user their welcome email')]
    #[RouteHandler([self::class, 'handle'])]
    public function send(): JobResult
    {
        return JobResult::ACK;
    }
}
