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

use App\Cli\Config;
use App\Cli\Controller\Abstract\Controller;
use App\Cli\Provider\AppCliRouteProvider;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Cli\Interaction\Message\Answer;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\Header;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Message\Question;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Attribute\Route\RouteHandler;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

class TestCommand extends Controller
{
    protected const string YES_ANSWER = 'yes';
    protected const string NO_ANSWER  = 'no';

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to showcase possibilities for commands.');
    }

    #[Route(
        name: 'test',
        description: 'Test command',
        helpText: [self::class, 'help'],
    )]
    #[RouteHandler([AppCliRouteProvider::class, 'testCommandHandler'])]
    public function run(RouteContract $route, CliConfigContract $config): OutputContract
    {
        /**
         * @var Config $config
         * @var string $namespace
         */
        $namespace = $config->namespace;
        /** @var string $version */
        $version = $config->version;

        return $this->outputFactory
            ->createOutput()
            ->withAddedMessages(
                new Header($namespace, $version, $route),
            )
            ->withAddedMessages(
                new NewLine(),
                new Question(
                    text: 'This is a question, right?',
                    callable: [$this, 'answered'],
                    answer: new Answer(
                        defaultResponse: self::NO_ANSWER,
                        allowedResponses: [
                            self::YES_ANSWER,
                            self::NO_ANSWER,
                        ]
                    )
                )
            );
    }

    /**
     * Callback for once the question is answered.
     */
    public function answered(OutputContract $output, AnswerContract $answer): OutputContract
    {
        if ($answer->getUserResponse() === self::YES_ANSWER) {
            return $output
                ->withAddedMessages(
                    new Message('You answered yes!!!'),
                    new NewLine(),
                    new NewLine(),
                )
                ->writeMessages();
        }

        return $output->withAddedMessages(new NewLine());
    }
}
