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

class Data
{
    /**
     * @var string
     */
    public string $property = 'hello';

    /**
     * @var string|null
     */
    public string|null $propertyNullable = null;
}
