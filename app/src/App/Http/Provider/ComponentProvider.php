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

namespace App\Http\Provider;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Application\Provider\HttpApplicationComponentProvider;
use Valkyrja\Container\Provider\ContainerServiceProvider;

final class ComponentProvider implements ComponentProviderContract
{
    /**
     * @inheritDoc
     */
    public static function publish(ApplicationContract $app): void
    {
        $container = $app->getContainer();

        if ($app->getDebugMode()) {
            ContainerServiceProvider::publishData(container: $container);

            return;
        }

        DataServiceProvider::publishContainerData(container: $container);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getComponentProviders(ApplicationContract $app): array
    {
        return [
            new HttpApplicationComponentProvider(),
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getContainerProviders(ApplicationContract $app): array
    {
        return [
            new DataServiceProvider(),
            new ServiceProvider(),
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEventProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCliProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHttpProviders(ApplicationContract $app): array
    {
        return [
            new HttpRouteProvider(),
        ];
    }
}
