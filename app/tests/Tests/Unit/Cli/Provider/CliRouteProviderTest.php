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

use App\Cli\Command\TestCommand;
use App\Cli\Config;
use App\Cli\Provider\CliRouteProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Container\Manager\Container;

final class CliRouteProviderTest extends TestCase
{
    public function testTestCommandHandler(): void
    {
        $output = self::createStub(OutputContract::class);
        $output->method('withAddedMessages')->willReturnSelf();

        $outputFactory = self::createStub(OutputFactoryContract::class);
        $outputFactory->method('createOutput')->willReturn($output);

        $container = new Container();
        $container->setSingleton(CliConfigContract::class, new Config());
        $container->setSingleton(
            TestCommand::class,
            new TestCommand(self::createStub(InputContract::class), $outputFactory)
        );

        self::assertInstanceOf(
            OutputContract::class,
            CliRouteProvider::testCommandHandler($container, self::createStub(RouteContract::class)),
        );
    }

    public function testGetControllerClasses(): void
    {
        self::assertSame([TestCommand::class], new CliRouteProvider()->getControllerClasses());
    }

    public function testGetRoutes(): void
    {
        self::assertSame([], new CliRouteProvider()->getRoutes());
    }
}
