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

namespace App\Model;

use Override;
use Valkyrja\Type\Model\Abstract\Model;

class SimpleModel extends Model
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    protected string $needsExtraLogic;

    /**
     * Getter for a property with extra logic.
     */
    protected function getNeedsExtraLogic(): string
    {
        // Do extra logic before getting

        return $this->needsExtraLogic;
    }

    /**
     * Setter for a property with extra logic.
     */
    protected function setNeedsExtraLogic(string $needsExtraLogic): void
    {
        // Do extra checks before setting

        $this->needsExtraLogic = $needsExtraLogic;
    }

    /**
     * Specify which method resolves a property on magic __get.
     *
     * @inheritDoc
     */
    #[Override]
    protected function internalGetCallables(): array
    {
        return [
            'needsExtraLogic' => [$this, 'getNeedsExtraLogic'],
        ];
    }

    /**
     * Specify which method resolves a property on magic __set.
     *
     * @inheritDoc
     */
    #[Override]
    protected function internalSetCallables(): array
    {
        return [
            'needsExtraLogic' => [$this, 'setNeedsExtraLogic'],
        ];
    }
}
