<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Tests\Unit\Model;

use App\Model\SimpleModel;
use PHPUnit\Framework\TestCase;
use Valkyrja\Type\Model\Abstract\Model;

final class SimpleModelTest extends TestCase
{
    public function testIsAModel(): void
    {
        self::assertInstanceOf(Model::class, new SimpleModel());
    }

    public function testNeedsExtraLogicUsesGetterAndSetterCallables(): void
    {
        $model = new SimpleModel();

        // __set routes through internalSetCallables() -> setNeedsExtraLogic().
        $model->needsExtraLogic = 'value';

        // __get routes through internalGetCallables() -> getNeedsExtraLogic().
        self::assertSame('value', $model->needsExtraLogic);
    }
}
