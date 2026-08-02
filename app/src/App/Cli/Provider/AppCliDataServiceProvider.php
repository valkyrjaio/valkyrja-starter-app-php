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

namespace App\Cli\Provider;

use App\Cli\Data\AppCliRoutingData;
use App\Cli\Data\AppContainerData;
use App\Cli\Data\AppEventData;
use App\Cli\Data\AppHttpRoutingData;
use Override;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Container\Data\ContainerData;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Event\Data\EventData;
use Valkyrja\Http\Routing\Data\HttpRoutingData;

final class AppCliDataServiceProvider implements ServiceProviderContract
{
    /**
     * Publish the container data.
     */
    public static function publishContainerData(ContainerContract $container): void
    {
        $container->setSingleton(ContainerData::class, new AppContainerData());
    }

    /**
     * Publish the event data.
     */
    public static function publishEventData(ContainerContract $container): void
    {
        $container->setSingleton(EventData::class, new AppEventData());
    }

    /**
     * Publish the cli routing data.
     */
    public static function publishCliRoutingData(ContainerContract $container): void
    {
        $container->setSingleton(CliRoutingData::class, new AppCliRoutingData());
    }

    /**
     * Publish the http routing data.
     */
    public static function publishHttpRoutingData(ContainerContract $container): void
    {
        $container->setSingleton(HttpRoutingData::class, new AppHttpRoutingData());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            ContainerData::class   => [self::class, 'publishContainerData'],
            EventData::class       => [self::class, 'publishEventData'],
            CliRoutingData::class  => [self::class, 'publishCliRoutingData'],
            HttpRoutingData::class => [self::class, 'publishHttpRoutingData'],
        ];
    }
}
