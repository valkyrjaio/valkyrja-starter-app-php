<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
