<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Generated\Cli\Data;

use App\Cli\Data\AppCliRoutingData;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Routing\Data\CliRoutingData;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;

/**
 * Assert the sindri-generated CLI-component {@see AppCliRoutingData} is populated.
 *
 * The routes must include the application's own `test` command discovered from
 * its CLI route provider, alongside the framework's built-in commands.
 */
final class AppCliRoutingDataTest extends TestCase
{
    public function testGeneratesPopulatedCliRoutingData(): void
    {
        $data = new AppCliRoutingData();

        self::assertInstanceOf(CliRoutingData::class, $data);

        self::assertNotEmpty($data->routes);
        // The application's own command must be present.
        self::assertArrayHasKey('test', $data->routes);
        // Framework built-in commands are discovered too.
        self::assertArrayHasKey('help', $data->routes);
        self::assertArrayHasKey('version', $data->routes);

        foreach ($data->routes as $routeFactory) {
            self::assertInstanceOf(RouteContract::class, $routeFactory());
        }
    }

    /**
     * Every argument and option permutation survives code generation with the modes,
     * value modes and metadata it was declared with, so the cached commands bind input
     * exactly as the runtime router would.
     */
    public function testGeneratesEveryArgumentAndOptionPermutation(): void
    {
        $data = new AppCliRoutingData();

        $names = [
            'permutations:argument-required',
            'permutations:argument-optional',
            'permutations:argument-array',
            'permutations:argument-required-array',
            'permutations:option-none',
            'permutations:option-default',
            'permutations:option-array',
            'permutations:option-required',
            'permutations:option-required-none',
            'permutations:option-required-array',
            'permutations:option-short',
            'permutations:option-valid-values',
            'permutations:option-default-value',
            'permutations:mixed',
        ];

        foreach ($names as $name) {
            self::assertArrayHasKey($name, $data->routes, "Command '$name' was not generated");
        }

        // Arguments keep their mode and value mode.
        $required = $this->route($data, 'permutations:argument-required')->getArgument('value');
        self::assertSame(ArgumentMode::REQUIRED, $required->getMode());
        self::assertSame(ArgumentValueMode::DEFAULT, $required->getValueMode());

        $optional = $this->route($data, 'permutations:argument-optional')->getArgument('value');
        self::assertSame(ArgumentMode::OPTIONAL, $optional->getMode());

        $array = $this->route($data, 'permutations:argument-array')->getArgument('values');
        self::assertSame(ArgumentValueMode::ARRAY, $array->getValueMode());

        $requiredArray = $this->route($data, 'permutations:argument-required-array')->getArgument('values');
        self::assertSame(ArgumentMode::REQUIRED, $requiredArray->getMode());
        self::assertSame(ArgumentValueMode::ARRAY, $requiredArray->getValueMode());

        // Options keep their mode and value mode.
        $flag = $this->route($data, 'permutations:option-none')->getOption('flag');
        self::assertSame(OptionMode::OPTIONAL, $flag->getMode());
        self::assertSame(OptionValueMode::NONE, $flag->getValueMode());

        $requiredOption = $this->route($data, 'permutations:option-required')->getOption('value');
        self::assertSame(OptionMode::REQUIRED, $requiredOption->getMode());
        self::assertSame(OptionValueMode::DEFAULT, $requiredOption->getValueMode());

        $arrayOption = $this->route($data, 'permutations:option-array')->getOption('tag');
        self::assertSame(OptionValueMode::ARRAY, $arrayOption->getValueMode());

        $requiredNone = $this->route($data, 'permutations:option-required-none')->getOption('flag');
        self::assertSame(OptionMode::REQUIRED, $requiredNone->getMode());
        self::assertSame(OptionValueMode::NONE, $requiredNone->getValueMode());

        // Short names, valid values and default values survive generation.
        self::assertSame(['m'], $this->route($data, 'permutations:option-short')->getOption('marker')->getShortNames());
        self::assertSame(
            ['json', 'xml'],
            $this->route($data, 'permutations:option-valid-values')->getOption('format')->getValidValues()
        );
        self::assertSame(
            'fallback',
            $this->route($data, 'permutations:option-default-value')->getOption('value')->getDefaultValue()
        );

        // A command may declare arguments and options together.
        $mixed = $this->route($data, 'permutations:mixed');
        self::assertTrue($mixed->hasArgument('name'));
        self::assertTrue($mixed->hasOption('tag'));
    }

    /**
     * Resolve a generated route by name.
     *
     * @param non-empty-string $name The command name
     */
    private function route(AppCliRoutingData $data, string $name): RouteContract
    {
        return $data->routes[$name]();
    }
}
