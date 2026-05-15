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

namespace App\Http\Provider;

use App\Http\Controller\HomeController;
use Override;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Container\Provider\Contract\ServiceProviderContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Factory\Contract\ResponseFactoryContract;

final class ServiceProvider implements ServiceProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function publishers(): array
    {
        return [
            HomeController::class => [self::class, 'publishHomeController'],
        ];
    }

    /**
     * Publish the home controller.
     */
    public static function publishHomeController(ContainerContract $container): void
    {
        $container->setSingleton(
            HomeController::class,
            new HomeController(
                $container->getSingleton(ServerRequestContract::class),
                $container->getSingleton(ResponseFactoryContract::class)
            )
        );
    }
}
