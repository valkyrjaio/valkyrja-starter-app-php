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

namespace App\Cli\Command;

use App\Cli\Controller\Abstract\Controller;
use App\Cli\Provider\CliRouteProvider;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\ArgumentParameter;
use Valkyrja\Cli\Routing\Attribute\OptionParameter;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Cli\Routing\Enum\ArgumentMode;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;

use function implode;

/**
 * Demonstrates every argument and option permutation the CLI router supports.
 *
 * Each command echoes back the value(s) bound to its parameters so that the
 * declaration, the generated routing data, and the runtime binding can all be
 * asserted end to end.
 */
class RoutingPermutationsCommand extends Controller
{
    /**
     * The help text shared by every permutation command.
     */
    public static function help(): MessageContract
    {
        return new Message('A command showcasing a CLI routing permutation.');
    }

    /**
     * A required, single-value argument.
     */
    #[Route(
        name: 'permutations:argument-required',
        description: 'A required single value argument',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsArgumentRequiredHandler'])]
    #[ArgumentParameter(
        name: 'value',
        description: 'A required single value argument',
        mode: ArgumentMode::REQUIRED,
        valueMode: ArgumentValueMode::DEFAULT,
    )]
    public function argumentRequired(string $value): OutputContract
    {
        return $this->message("argument-required:$value");
    }

    /**
     * An optional, single-value argument.
     */
    #[Route(
        name: 'permutations:argument-optional',
        description: 'An optional single value argument',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsArgumentOptionalHandler'])]
    #[ArgumentParameter(
        name: 'value',
        description: 'An optional single value argument',
        mode: ArgumentMode::OPTIONAL,
        valueMode: ArgumentValueMode::DEFAULT,
    )]
    public function argumentOptional(string $value): OutputContract
    {
        return $this->message("argument-optional:$value");
    }

    /**
     * An optional array argument, which consumes every remaining input argument.
     *
     * @param string[] $values
     */
    #[Route(
        name: 'permutations:argument-array',
        description: 'An optional array argument',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsArgumentArrayHandler'])]
    #[ArgumentParameter(
        name: 'values',
        description: 'An optional array argument',
        mode: ArgumentMode::OPTIONAL,
        valueMode: ArgumentValueMode::ARRAY,
    )]
    public function argumentArray(array $values): OutputContract
    {
        return $this->message('argument-array:' . implode(',', $values));
    }

    /**
     * A required array argument.
     *
     * @param string[] $values
     */
    #[Route(
        name: 'permutations:argument-required-array',
        description: 'A required array argument',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsArgumentRequiredArrayHandler'])]
    #[ArgumentParameter(
        name: 'values',
        description: 'A required array argument',
        mode: ArgumentMode::REQUIRED,
        valueMode: ArgumentValueMode::ARRAY,
    )]
    public function argumentRequiredArray(array $values): OutputContract
    {
        return $this->message('argument-required-array:' . implode(',', $values));
    }

    /**
     * An optional option that takes no value at all — a flag.
     */
    #[Route(
        name: 'permutations:option-none',
        description: 'An optional valueless flag option',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionNoneHandler'])]
    #[OptionParameter(
        name: 'flag',
        description: 'An optional valueless flag option',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::NONE,
    )]
    public function optionNone(bool $isProvided): OutputContract
    {
        return $this->message('option-none:' . ($isProvided ? 'yes' : 'no'));
    }

    /**
     * An optional option that takes a single value.
     */
    #[Route(
        name: 'permutations:option-default',
        description: 'An optional single value option',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionDefaultHandler'])]
    #[OptionParameter(
        name: 'value',
        description: 'An optional single value option',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::DEFAULT,
    )]
    public function optionDefault(string $value): OutputContract
    {
        return $this->message("option-default:$value");
    }

    /**
     * An optional option that can be repeated.
     *
     * @param string[] $values
     */
    #[Route(
        name: 'permutations:option-array',
        description: 'An optional repeatable option',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionArrayHandler'])]
    #[OptionParameter(
        name: 'tag',
        description: 'An optional repeatable option',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::ARRAY,
    )]
    public function optionArray(array $values): OutputContract
    {
        return $this->message('option-array:' . implode(',', $values));
    }

    /**
     * A required option that takes a single value.
     */
    #[Route(
        name: 'permutations:option-required',
        description: 'A required single value option',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionRequiredHandler'])]
    #[OptionParameter(
        name: 'value',
        description: 'A required single value option',
        mode: OptionMode::REQUIRED,
        valueMode: OptionValueMode::DEFAULT,
    )]
    public function optionRequired(string $value): OutputContract
    {
        return $this->message("option-required:$value");
    }

    /**
     * A required flag option that takes no value.
     */
    #[Route(
        name: 'permutations:option-required-none',
        description: 'A required valueless flag option',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionRequiredNoneHandler'])]
    #[OptionParameter(
        name: 'flag',
        description: 'A required valueless flag option',
        mode: OptionMode::REQUIRED,
        valueMode: OptionValueMode::NONE,
    )]
    public function optionRequiredNone(bool $isProvided): OutputContract
    {
        return $this->message('option-required-none:' . ($isProvided ? 'yes' : 'no'));
    }

    /**
     * A required option that can be repeated.
     *
     * @param string[] $values
     */
    #[Route(
        name: 'permutations:option-required-array',
        description: 'A required repeatable option',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionRequiredArrayHandler'])]
    #[OptionParameter(
        name: 'tag',
        description: 'A required repeatable option',
        mode: OptionMode::REQUIRED,
        valueMode: OptionValueMode::ARRAY,
    )]
    public function optionRequiredArray(array $values): OutputContract
    {
        return $this->message('option-required-array:' . implode(',', $values));
    }

    /**
     * An option that may also be given by one of its short names.
     *
     * Short names must avoid the framework's global ones — `h` (help), `v` (version),
     * `q` (quiet), `s` (silent), `N` (no interaction) and `t` (token) — because the
     * global middleware handles those before a command is ever dispatched.
     */
    #[Route(
        name: 'permutations:option-short',
        description: 'An option with short names',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionShortHandler'])]
    #[OptionParameter(
        name: 'marker',
        description: 'An option with short names',
        shortNames: ['m'],
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::NONE,
    )]
    public function optionShort(bool $isProvided): OutputContract
    {
        return $this->message('option-short:' . ($isProvided ? 'yes' : 'no'));
    }

    /**
     * An option restricted to a fixed set of valid values.
     */
    #[Route(
        name: 'permutations:option-valid-values',
        description: 'An option restricted to valid values',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionValidValuesHandler'])]
    #[OptionParameter(
        name: 'format',
        description: 'An option restricted to valid values',
        validValues: ['json', 'xml'],
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::DEFAULT,
    )]
    public function optionValidValues(string $value): OutputContract
    {
        return $this->message("option-valid-values:$value");
    }

    /**
     * An option that falls back to its declared default when it is not given.
     */
    #[Route(
        name: 'permutations:option-default-value',
        description: 'An option with a default value',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsOptionDefaultValueHandler'])]
    #[OptionParameter(
        name: 'value',
        description: 'An option with a default value',
        defaultValue: 'fallback',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::DEFAULT,
    )]
    public function optionDefaultValue(string $value): OutputContract
    {
        return $this->message("option-default-value:$value");
    }

    /**
     * Arguments and options declared together on one command.
     *
     * @param string[] $tags
     */
    #[Route(
        name: 'permutations:mixed',
        description: 'Arguments and options together',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([CliRouteProvider::class, 'permutationsMixedHandler'])]
    #[ArgumentParameter(
        name: 'name',
        description: 'A required single value argument',
        mode: ArgumentMode::REQUIRED,
        valueMode: ArgumentValueMode::DEFAULT,
    )]
    #[OptionParameter(
        name: 'tag',
        description: 'An optional repeatable option',
        mode: OptionMode::OPTIONAL,
        valueMode: OptionValueMode::ARRAY,
    )]
    public function mixed(string $name, array $tags): OutputContract
    {
        return $this->message("mixed:$name:" . implode(',', $tags));
    }

    /**
     * Build a single message output.
     *
     * @param non-empty-string $text The text
     */
    protected function message(string $text): OutputContract
    {
        return $this->outputFactory
            ->createOutput()
            ->withAddedMessages(new Message($text));
    }
}
