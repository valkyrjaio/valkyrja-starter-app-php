<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Cli\Command;

use App\Cli\Command\RoutingPermutationsCommand;
use Closure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\OutputFactory;

final class RoutingPermutationsCommandTest extends TestCase
{
    private RoutingPermutationsCommand $command;

    /**
     * Every permutation command echoes the value(s) bound to its parameters.
     *
     * @return array<non-empty-string, array{Closure(RoutingPermutationsCommand): OutputContract, non-empty-string}>
     */
    public static function permutationProvider(): array
    {
        return [
            'argument required'           => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->argumentRequired('foo'), 'argument-required:foo'],
            'argument optional'           => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->argumentOptional('bar'), 'argument-optional:bar'],
            'argument array'              => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->argumentArray(['a', 'b']), 'argument-array:a,b'],
            'argument required array'     => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->argumentRequiredArray(['x']), 'argument-required-array:x'],
            'option none given'           => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionNone(true), 'option-none:yes'],
            'option none absent'          => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionNone(false), 'option-none:no'],
            'option default'              => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionDefault('hello'), 'option-default:hello'],
            'option array'                => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionArray(['x', 'y']), 'option-array:x,y'],
            'option required'             => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionRequired('req'), 'option-required:req'],
            'option required none'        => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionRequiredNone(true), 'option-required-none:yes'],
            'option required none absent' => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionRequiredNone(false), 'option-required-none:no'],
            'option required array'       => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionRequiredArray(['one']), 'option-required-array:one'],
            'option short given'          => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionShort(true), 'option-short:yes'],
            'option short absent'         => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionShort(false), 'option-short:no'],
            'option valid values'         => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionValidValues('json'), 'option-valid-values:json'],
            'option default value'        => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->optionDefaultValue('fallback'), 'option-default-value:fallback'],
            'mixed'                       => [static fn (RoutingPermutationsCommand $c): OutputContract => $c->mixed('bob', ['t1', 't2']), 'mixed:bob:t1,t2'],
        ];
    }

    protected function setUp(): void
    {
        $this->command = new RoutingPermutationsCommand(
            self::createStub(InputContract::class),
            new OutputFactory()
        );
    }

    public function testHelpReturnsMessage(): void
    {
        self::assertInstanceOf(MessageContract::class, RoutingPermutationsCommand::help());
    }

    /**
     * @param Closure(RoutingPermutationsCommand): OutputContract $action
     * @param non-empty-string                                    $expected
     */
    #[DataProvider('permutationProvider')]
    public function testPermutationEchoesBoundValues(Closure $action, string $expected): void
    {
        $output = $action($this->command);

        $messages = $output->getMessages();

        self::assertCount(1, $messages);
        self::assertSame($expected, $messages[0]->getText());
    }
}
