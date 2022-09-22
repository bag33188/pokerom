<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum UserRoleEnum: string
{
    use EnumMethods {
        values as public;
    }

    /** administrator */
    case ADMIN = 'admin';
    /** default user */
    case DEFAULT = 'user';
    /** guest user */
    case GUEST = 'guest';
}
