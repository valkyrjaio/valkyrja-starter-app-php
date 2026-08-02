<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Orm\Entity;

use App\Orm\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testGetTableName(): void
    {
        self::assertSame('users', User::getTableName());
    }

    public function testNeedsExtraLogicUsesGetterAndSetterCallables(): void
    {
        $user = new User();

        // __set routes through internalSetCallables() -> setNeedsExtraLogic().
        $user->needsExtraLogic = 'value';

        // __get routes through internalGetCallables() -> getNeedsExtraLogic().
        self::assertSame('value', $user->needsExtraLogic);
    }
}
