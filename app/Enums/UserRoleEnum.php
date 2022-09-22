<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum UserRoleEnum: string
{
    use EnumMethods;

    /** administrator */
    case ADMIN = 'admin';
    /** default user */
    case DEFAULT = 'user';
    /** guest user */
    case GUEST = 'guest';
}
