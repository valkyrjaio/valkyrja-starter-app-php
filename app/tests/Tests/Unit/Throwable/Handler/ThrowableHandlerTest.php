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

namespace App\Tests\Unit\Throwable\Handler;

use App\Throwable\Handler\ThrowableHandler;
use PHPUnit\Framework\TestCase;
use Valkyrja\Throwable\Handler\Contract\ThrowableHandlerContract;

final class ThrowableHandlerTest extends TestCase
{
    public function testIsAThrowableHandler(): void
    {
        self::assertInstanceOf(ThrowableHandlerContract::class, new ThrowableHandler());
    }

    public function testEnableIsANoOp(): void
    {
        new ThrowableHandler()->enable(displayErrors: true);

        self::assertTrue(true);
    }
}
