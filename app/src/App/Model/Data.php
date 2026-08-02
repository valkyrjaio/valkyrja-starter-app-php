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
