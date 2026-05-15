<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Cli\Provider;

use App\Cli\Command\TestCommand;
use Override;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;

final class CliRouteProvider implements CliRouteProviderContract
{
    /**
     * The test command handler.
     */
    public static function testCommandHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return $container->getSingleton(TestCommand::class)->run(
            $route,
            $container->getSingleton(CliConfigContract::class),
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getControllerClasses(): array
    {
        return [
            TestCommand::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getRoutes(): array
    {
        return [];
    }
}
