<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Application package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace App\Orm\Repository;

use App\Orm\Entity\User;
use Valkyrja\Orm\Repository\Repository;

/**
 * @extends Repository<User>
 */
class UserRepository extends Repository
{
    // We can do custom stuff for all User entities.
    //  Examples:
    //      $entityManager->getRepository(User::class)->create(new User());
}
