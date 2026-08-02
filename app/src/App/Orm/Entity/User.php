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

namespace App\Orm\Entity;

use Override;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Entity\Contract\VerifiableUserContract;
use Valkyrja\Auth\Entity\Trait\MailableUserMethods;
use Valkyrja\Auth\Entity\Trait\UserMethods;
use Valkyrja\Auth\Entity\Trait\VerifiableUserMethods;
use Valkyrja\Orm\Entity\Abstract\Entity;
use Valkyrja\Orm\Entity\Contract\DatedEntityContract;
use Valkyrja\Orm\Entity\Contract\SoftDeleteEntityContract;
use Valkyrja\Orm\Entity\Trait\DatedFields;
use Valkyrja\Orm\Entity\Trait\SoftDeleteFields;

class User extends Entity implements UserContract, DatedEntityContract, SoftDeleteEntityContract, VerifiableUserContract
{
    use DatedFields;
    use MailableUserMethods;
    use SoftDeleteFields;
    use UserMethods;
    use VerifiableUserMethods;

    /**
     * A field that requires extra logic too complex for a property hook.
     *
     * @var non-empty-string
     */
    protected string $needsExtraLogic;

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return 'users';
    }

    /**
     * Getter for a property that needs extra logic before getting.
     *
     * @return non-empty-string
     */
    protected function getNeedsExtraLogic(): string
    {
        // Do extra logic before getting

        return $this->needsExtraLogic;
    }

    /**
     * Setter for a property that needs extra logic before setting.
     *
     * @param non-empty-string $needsExtraLogic The value
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
