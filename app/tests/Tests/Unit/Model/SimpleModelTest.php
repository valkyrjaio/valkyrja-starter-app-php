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
