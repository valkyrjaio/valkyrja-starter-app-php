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

use App\Cli\Command\RoutingPermutationsCommand;
use App\Cli\Command\TestCommand;
use Override;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Interaction\Argument\Contract\ArgumentContract;
use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Provider\Contract\CliRouteProviderContract;
use Valkyrja\Container\Manager\Contract\ContainerContract;

use function array_map;

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

    public static function permutationsArgumentRequiredHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->argumentRequired(self::argumentValue($route, 'value'));
    }

    public static function permutationsArgumentOptionalHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->argumentOptional(self::argumentValue($route, 'value'));
    }

    public static function permutationsArgumentArrayHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->argumentArray(self::argumentValues($route, 'values'));
    }

    public static function permutationsArgumentRequiredArrayHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->argumentRequiredArray(self::argumentValues($route, 'values'));
    }

    public static function permutationsOptionNoneHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionNone(self::hasOption($route, 'flag'));
    }

    public static function permutationsOptionDefaultHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionDefault(self::optionValue($route, 'value'));
    }

    public static function permutationsOptionArrayHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionArray(self::optionValues($route, 'tag'));
    }

    public static function permutationsOptionRequiredHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionRequired(self::optionValue($route, 'value'));
    }

    public static function permutationsOptionRequiredNoneHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionRequiredNone(self::hasOption($route, 'flag'));
    }

    public static function permutationsOptionRequiredArrayHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionRequiredArray(self::optionValues($route, 'tag'));
    }

    public static function permutationsOptionShortHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionShort(self::hasOption($route, 'marker'));
    }

    public static function permutationsOptionValidValuesHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->optionValidValues(self::optionValue($route, 'format'));
    }

    public static function permutationsOptionDefaultValueHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        $option = $route->getOption('value');
        // An option that was not given falls back to the default it declared.
        $value = $option->hasFirstValue()
            ? $option->getFirstValue()
            : $option->getDefaultValue();

        return self::permutationsCommand($container)->optionDefaultValue($value);
    }

    public static function permutationsMixedHandler(ContainerContract $container, RouteContract $route): OutputContract
    {
        return self::permutationsCommand($container)->mixed(
            self::argumentValue($route, 'name'),
            self::optionValues($route, 'tag')
        );
    }

    /**
     * Get the routing permutations command.
     */
    private static function permutationsCommand(ContainerContract $container): RoutingPermutationsCommand
    {
        return $container->getSingleton(RoutingPermutationsCommand::class);
    }

    /**
     * Get the first value bound to an argument.
     *
     * @param non-empty-string $name The argument name
     */
    private static function argumentValue(RouteContract $route, string $name): string
    {
        return $route->getArgument($name)->getFirstValue();
    }

    /**
     * Get every value bound to an argument.
     *
     * @param non-empty-string $name The argument name
     *
     * @return string[]
     */
    private static function argumentValues(RouteContract $route, string $name): array
    {
        return array_map(
            static fn (ArgumentContract $argument): string => $argument->getValue(),
            $route->getArgument($name)->getArguments()
        );
    }

    /**
     * Determine whether an option was provided.
     *
     * @param non-empty-string $name The option name
     */
    private static function hasOption(RouteContract $route, string $name): bool
    {
        return $route->getOption($name)->getOptions() !== [];
    }

    /**
     * Get the first value bound to an option.
     *
     * @param non-empty-string $name The option name
     */
    private static function optionValue(RouteContract $route, string $name): string
    {
        return $route->getOption($name)->getFirstValue();
    }

    /**
     * Get every value bound to an option.
     *
     * @param non-empty-string $name The option name
     *
     * @return string[]
     */
    private static function optionValues(RouteContract $route, string $name): array
    {
        return array_map(
            static fn (OptionContract $option): string => $option->getValue(),
            $route->getOption($name)->getOptions()
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
            RoutingPermutationsCommand::class,
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
