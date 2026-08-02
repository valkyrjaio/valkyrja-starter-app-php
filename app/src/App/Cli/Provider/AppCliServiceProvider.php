<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Cli\Provider;

use App\Cli\Command\RoutingPermutationsCommand;
use App\Cli\Command\TestCommand;
use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;

final class AppCliServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the test command.
     */
    public static function publishTestCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            TestCommand::class,
            new TestCommand(
                $container->getSingleton(InputContract::class),
                $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }

    /**
     * Publish the routing permutations command.
     */
    public static function publishRoutingPermutationsCommand(ContainerContract $container): void
    {
        $container->setSingleton(
            RoutingPermutationsCommand::class,
            new RoutingPermutationsCommand(
                $container->getSingleton(InputContract::class),
                $container->getSingleton(OutputFactoryContract::class),
            )
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            TestCommand::class                => [self::class, 'publishTestCommand'],
            RoutingPermutationsCommand::class => [self::class, 'publishRoutingPermutationsCommand'],
        ];
    }
}
