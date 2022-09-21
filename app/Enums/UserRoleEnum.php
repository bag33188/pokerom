<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait;

enum UserRoleEnum: string
{
    use EnumMethodsTrait {
        values as public;
    }

    /** administrator */
    case ADMIN = 'admin';
    /** default user */
    case DEFAULT = 'user';
    /** guest user */
    case GUEST = 'guest';
}
