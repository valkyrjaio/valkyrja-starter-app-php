<?php

// phpcs:ignoreFile

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Cli;

use App\Cli\Provider\ComponentProvider;
use App\Http\Config as AppHttpConfig;
use Valkyrja\Application\Data\CliConfig;
use Valkyrja\Application\Provider\CliWithHttpApplicationComponentProvider;
use Valkyrja\Cli\Server\Constant\CommandName;

final class Config extends CliConfig
{
    public function __construct()
    {
        parent::__construct(
            namespace: 'App',
            dir: __DIR__ . '/../../..',
            version: '1.0.0',
            environment: 'production',
            debugMode: true,
            timezone: 'UTC',
            key: 'some_secret_app_key',
            dataPath: 'App/Cli/Data',
            dataNamespace: 'App\\Cli\\Data',
            applicationName: 'cli',
            defaultCommandName: CommandName::LIST,
            providers: [
                CliWithHttpApplicationComponentProvider::class,
                ComponentProvider::class,
            ],
            callbacks: [
                [ComponentProvider::class, 'publish'],
            ],
            http: new AppHttpConfig(),
        );
    }
}
