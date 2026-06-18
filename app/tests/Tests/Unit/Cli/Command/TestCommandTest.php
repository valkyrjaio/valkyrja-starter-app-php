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

namespace App\Tests\Unit\Cli\Command;

use App\Cli\Command\TestCommand;
use App\Cli\Config;
use PHPUnit\Framework\TestCase;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Message\Contract\AnswerContract;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

final class TestCommandTest extends TestCase
{
    private OutputContract $output;

    private TestCommand $command;

    protected function setUp(): void
    {
        $this->output = self::createStub(OutputContract::class);
        $this->output->method('withAddedMessages')->willReturnSelf();
        $this->output->method('writeMessages')->willReturnSelf();

        $outputFactory = self::createStub(OutputFactoryContract::class);
        $outputFactory->method('createOutput')->willReturn($this->output);

        $this->command = new TestCommand(self::createStub(InputContract::class), $outputFactory);
    }

    public function testHelpReturnsMessage(): void
    {
        self::assertInstanceOf(MessageContract::class, TestCommand::help());
    }

    public function testRunReturnsOutput(): void
    {
        self::assertInstanceOf(
            OutputContract::class,
            $this->command->run(self::createStub(RouteContract::class), new Config()),
        );
    }

    public function testAnsweredYes(): void
    {
        $answer = self::createStub(AnswerContract::class);
        $answer->method('getUserResponse')->willReturn('yes');

        self::assertInstanceOf(OutputContract::class, $this->command->answered($this->output, $answer));
    }

    public function testAnsweredNo(): void
    {
        $answer = self::createStub(AnswerContract::class);
        $answer->method('getUserResponse')->willReturn('no');

        self::assertInstanceOf(OutputContract::class, $this->command->answered($this->output, $answer));
    }
}
