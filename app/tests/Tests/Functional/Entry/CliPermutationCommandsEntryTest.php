<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Functional\Entry;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function dirname;
use function escapeshellarg;
use function exec;
use function implode;

use const PHP_BINARY;

/**
 * End-to-end test for every argument and option permutation the application declares.
 *
 * Runs the real console binary in a subprocess for each permutation and asserts the
 * command matched, bound its input, and echoed the expected values — and that invalid
 * input is rejected with a non-zero exit code. This runs against the sindri-generated
 * command data, so it proves the generated cache binds input exactly as the runtime
 * router would.
 */
final class CliPermutationCommandsEntryTest extends TestCase
{
    /**
     * @return array<non-empty-string, array{non-empty-string, non-empty-string}>
     */
    public static function permutationProvider(): array
    {
        return [
            'required argument'          => ['permutations:argument-required foo', 'argument-required:foo'],
            'optional argument'          => ['permutations:argument-optional bar', 'argument-optional:bar'],
            'array argument'             => ['permutations:argument-array a b c', 'argument-array:a,b,c'],
            'required array argument'    => ['permutations:argument-required-array x y', 'argument-required-array:x,y'],
            'flag option given'          => ['permutations:option-none --flag', 'option-none:yes'],
            'flag option absent'         => ['permutations:option-none', 'option-none:no'],
            'value option'               => ['permutations:option-default --value=hello', 'option-default:hello'],
            'repeatable option'          => ['permutations:option-array --tag=x --tag=y', 'option-array:x,y'],
            'required option'            => ['permutations:option-required --value=req', 'option-required:req'],
            'required flag option'       => ['permutations:option-required-none --flag', 'option-required-none:yes'],
            'required repeatable option' => ['permutations:option-required-array --tag=one', 'option-required-array:one'],
            'option by long name'        => ['permutations:option-short --marker', 'option-short:yes'],
            'option by short name'       => ['permutations:option-short -m', 'option-short:yes'],
            'option absent'              => ['permutations:option-short', 'option-short:no'],
            'valid value accepted'       => ['permutations:option-valid-values --format=json', 'option-valid-values:json'],
            'default value used'         => ['permutations:option-default-value', 'option-default-value:fallback'],
            'given value overrides'      => ['permutations:option-default-value --value=given', 'option-default-value:given'],
            'arguments and options'      => ['permutations:mixed bob --tag=t1 --tag=t2', 'mixed:bob:t1,t2'],
        ];
    }

    /**
     * @return array<non-empty-string, array{non-empty-string, non-empty-string}>
     */
    public static function invalidInputProvider(): array
    {
        return [
            'value outside the valid values' => ['permutations:option-valid-values --format=csv', 'format is invalid'],
            'required option missing'        => ['permutations:option-required', 'value is invalid'],
            'required argument missing'      => ['permutations:argument-required', 'value is invalid'],
            'required flag option missing'   => ['permutations:option-required-none', 'flag is invalid'],
            'value given to a flag option'   => ['permutations:option-none --flag=oops', 'flag should have no value'],
        ];
    }

    /**
     * @param non-empty-string $arguments
     * @param non-empty-string $expected
     */
    #[DataProvider('permutationProvider')]
    public function testPermutationBindsInputAndEchoesIt(string $arguments, string $expected): void
    {
        [$stdout, $exitCode] = $this->runCli($arguments);

        self::assertSame(0, $exitCode, "`$arguments` did not exit cleanly:\n$stdout");
        self::assertStringContainsString($expected, $stdout);
        self::assertStringNotContainsString('Fatal error', $stdout);
        self::assertStringNotContainsString('Uncaught', $stdout);
    }

    /**
     * @param non-empty-string $arguments
     * @param non-empty-string $expected
     */
    #[DataProvider('invalidInputProvider')]
    public function testInvalidInputIsRejected(string $arguments, string $expected): void
    {
        [$stdout, $exitCode] = $this->runCli($arguments);

        self::assertSame(1, $exitCode, "`$arguments` should have been rejected:\n$stdout");
        self::assertStringContainsString($expected, $stdout);
        self::assertStringNotContainsString('Fatal error', $stdout);
    }

    /**
     * Run the console binary and return its output and exit code.
     *
     * @param non-empty-string $arguments
     *
     * @return array{0: string, 1: int}
     */
    private function runCli(string $arguments): array
    {
        $cli = dirname(__DIR__, 5) . '/app/bin/cli';

        $command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($cli) . ' ' . $arguments . ' 2>&1';

        $output   = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        return [implode("\n", $output), $exitCode];
    }
}
