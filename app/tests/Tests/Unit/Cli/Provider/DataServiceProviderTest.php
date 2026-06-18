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

use App\Cli\Data\AppCliRoutingData;
use App\Cli\Data\AppContainerData;
use App\Cli\Data\AppEventData;
use App\Cli\Data\AppHttpRoutingData;
use App\Cli\Provider\DataServiceProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class DataServiceProviderTest extends TestCase
{
    public function testPublishContainerData(): void
    {
        $container = new Container();

        DataServiceProvider::publishContainerData($container);

        self::assertInstanceOf(AppContainerData::class, $container->getSingleton(ContainerData::class));
    }

    public function testPublishEventData(): void
    {
        $container = new Container();

        DataServiceProvider::publishEventData($container);

        self::assertInstanceOf(AppEventData::class, $container->getSingleton(EventData::class));
    }

    public function testPublishCliRoutingData(): void
    {
        $container = new Container();

        DataServiceProvider::publishCliRoutingData($container);

        self::assertInstanceOf(AppCliRoutingData::class, $container->getSingleton(CliRoutingData::class));
    }

    public function testPublishHttpRoutingData(): void
    {
        $container = new Container();

        DataServiceProvider::publishHttpRoutingData($container);

        self::assertInstanceOf(AppHttpRoutingData::class, $container->getSingleton(HttpRoutingData::class));
    }

    public function testPublishers(): void
    {
        self::assertSame(
            [
                ContainerData::class   => [DataServiceProvider::class, 'publishContainerData'],
                EventData::class       => [DataServiceProvider::class, 'publishEventData'],
                CliRoutingData::class  => [DataServiceProvider::class, 'publishCliRoutingData'],
                HttpRoutingData::class => [DataServiceProvider::class, 'publishHttpRoutingData'],
            ],
            new DataServiceProvider()->publishers(),
        );
    }
}
