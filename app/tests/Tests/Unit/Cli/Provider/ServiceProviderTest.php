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

namespace App\Tests\Unit\Cli\Provider;

use App\Cli\Command\RoutingPermutationsCommand;
use App\Cli\Command\TestCommand;
use App\Cli\Provider\ServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Container\Manager\Container;

final class ServiceProviderTest extends TestCase
{
    public function testPublishTestCommand(): void
    {
        $container = new Container();
        $container->setSingleton(InputContract::class, self::createStub(InputContract::class));
        $container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        ServiceProvider::publishTestCommand($container);

        self::assertInstanceOf(TestCommand::class, $container->getSingleton(TestCommand::class));
    }

    public function testPublishRoutingPermutationsCommand(): void
    {
        $container = new Container();
        $container->setSingleton(InputContract::class, self::createStub(InputContract::class));
        $container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        ServiceProvider::publishRoutingPermutationsCommand($container);

        self::assertInstanceOf(
            RoutingPermutationsCommand::class,
            $container->getSingleton(RoutingPermutationsCommand::class)
        );
    }

    public function testPublishers(): void
    {
        self::assertSame(
            [
                TestCommand::class                => [ServiceProvider::class, 'publishTestCommand'],
                RoutingPermutationsCommand::class => [ServiceProvider::class, 'publishRoutingPermutationsCommand'],
            ],
            new ServiceProvider()->publishers(),
        );
    }
}
