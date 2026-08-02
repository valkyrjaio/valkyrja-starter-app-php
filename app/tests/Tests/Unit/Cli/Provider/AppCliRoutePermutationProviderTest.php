<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Cli\Provider;

use App\Cli\Command\RoutingPermutationsCommand;
use App\Cli\Provider\AppCliRouteProvider;
use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Interaction\Argument\Argument;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Container\Manager\Container;
use Valkyrja\Container\Manager\Contract\ContainerContract;

use function array_map;

/**
 * Assert every routing permutation handler binds its route's arguments and options and
 * delegates to the command with the values a real invocation would produce.
 */
final class AppCliRoutePermutationProviderTest extends TestCase
{
    private ContainerContract $container;

    /**
     * @return array<non-empty-string, array{Closure(ContainerContract, RouteContract): OutputContract, array<non-empty-string, string[]>, array<non-empty-string, string[]>, non-empty-string}>
     */
    public static function permutationHandlerProvider(): array
    {
        return [
            'argument required'       => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsArgumentRequiredHandler($c, $r), ['value' => ['foo']], [], 'argument-required:foo'],
            'argument optional'       => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsArgumentOptionalHandler($c, $r), ['value' => ['bar']], [], 'argument-optional:bar'],
            'argument array'          => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsArgumentArrayHandler($c, $r), ['values' => ['a', 'b', 'c']], [], 'argument-array:a,b,c'],
            'argument required array' => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsArgumentRequiredArrayHandler($c, $r), ['values' => ['x', 'y']], [], 'argument-required-array:x,y'],
            'option none given'       => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionNoneHandler($c, $r), [], ['flag' => ['']], 'option-none:yes'],
            'option none absent'      => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionNoneHandler($c, $r), [], ['flag' => []], 'option-none:no'],
            'option default'          => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionDefaultHandler($c, $r), [], ['value' => ['hello']], 'option-default:hello'],
            'option array'            => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionArrayHandler($c, $r), [], ['tag' => ['x', 'y']], 'option-array:x,y'],
            'option required'         => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionRequiredHandler($c, $r), [], ['value' => ['req']], 'option-required:req'],
            'option required none'    => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionRequiredNoneHandler($c, $r), [], ['flag' => ['']], 'option-required-none:yes'],
            'option required array'   => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionRequiredArrayHandler($c, $r), [], ['tag' => ['one']], 'option-required-array:one'],
            'option short given'      => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionShortHandler($c, $r), [], ['marker' => ['']], 'option-short:yes'],
            'option short absent'     => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionShortHandler($c, $r), [], ['marker' => []], 'option-short:no'],
            'option valid values'     => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsOptionValidValuesHandler($c, $r), [], ['format' => ['json']], 'option-valid-values:json'],
            'mixed'                   => [static fn (ContainerContract $c, RouteContract $r): OutputContract => AppCliRouteProvider::permutationsMixedHandler($c, $r), ['name' => ['bob']], ['tag' => ['t1', 't2']], 'mixed:bob:t1,t2'],
        ];
    }

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->container->setSingleton(
            RoutingPermutationsCommand::class,
            new RoutingPermutationsCommand(self::createStub(InputContract::class), new OutputFactory())
        );
    }

    /**
     * @param Closure(ContainerContract, RouteContract): OutputContract $handler
     * @param array<non-empty-string, string[]>                         $arguments
     * @param array<non-empty-string, string[]>                         $options
     * @param non-empty-string                                          $expected
     */
    #[DataProvider('permutationHandlerProvider')]
    public function testHandlerBindsAndDelegates(Closure $handler, array $arguments, array $options, string $expected): void
    {
        $output = $handler($this->container, $this->route($arguments, $options));

        $messages = $output->getMessages();

        self::assertCount(1, $messages);
        self::assertSame($expected, $messages[0]->getText());
    }

    public function testOptionDefaultValueHandlerUsesTheDeclaredDefaultWhenAbsent(): void
    {
        $route = $this->route([], [], defaultValue: 'fallback');

        $output = AppCliRouteProvider::permutationsOptionDefaultValueHandler($this->container, $route);

        self::assertSame('option-default-value:fallback', $output->getMessages()[0]->getText());
    }

    public function testOptionDefaultValueHandlerUsesTheGivenValue(): void
    {
        $route = $this->route([], ['value' => ['given']], defaultValue: 'fallback');

        $output = AppCliRouteProvider::permutationsOptionDefaultValueHandler($this->container, $route);

        self::assertSame('option-default-value:given', $output->getMessages()[0]->getText());
    }

    /**
     * Build a route whose arguments and options resolve to the given name => values pairs.
     *
     * @param array<non-empty-string, string[]> $arguments
     * @param array<non-empty-string, string[]> $options
     */
    private function route(array $arguments, array $options, string $defaultValue = ''): RouteContract
    {
        $route = self::createStub(RouteContract::class);

        $route->method('getArgument')
            ->willReturnCallback(
                static fn (string $name): ArgumentParameter => new ArgumentParameter(
                    name: $name,
                    description: 'description',
                    arguments: array_map(
                        static fn (string $value): Argument => new Argument(value: $value),
                        $arguments[$name] ?? []
                    )
                )
            );

        $route->method('getOption')
            ->willReturnCallback(
                static fn (string $name): OptionParameter => new OptionParameter(
                    name: $name,
                    description: 'description',
                    defaultValue: $defaultValue,
                    options: array_map(
                        static fn (string $value): Option => new Option(name: $name, value: $value),
                        $options[$name] ?? []
                    )
                )
            );

        return $route;
    }
}
