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
use App\Cli\Provider\AppCliServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Container\Manager\Container;

final class AppCliServiceProviderTest extends TestCase
{
    public function testPublishTestCommand(): void
    {
        $container = new Container();
        $container->setSingleton(InputContract::class, self::createStub(InputContract::class));
        $container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        AppCliServiceProvider::publishTestCommand($container);

        self::assertInstanceOf(TestCommand::class, $container->getSingleton(TestCommand::class));
    }

    public function testPublishRoutingPermutationsCommand(): void
    {
        $container = new Container();
        $container->setSingleton(InputContract::class, self::createStub(InputContract::class));
        $container->setSingleton(OutputFactoryContract::class, self::createStub(OutputFactoryContract::class));

        AppCliServiceProvider::publishRoutingPermutationsCommand($container);

        self::assertInstanceOf(
            RoutingPermutationsCommand::class,
            $container->getSingleton(RoutingPermutationsCommand::class)
        );
    }

    public function testPublishers(): void
    {
        self::assertSame(
            [
                TestCommand::class                => [AppCliServiceProvider::class, 'publishTestCommand'],
                RoutingPermutationsCommand::class => [AppCliServiceProvider::class, 'publishRoutingPermutationsCommand'],
            ],
            new AppCliServiceProvider()->publishers(),
        );
    }
}
