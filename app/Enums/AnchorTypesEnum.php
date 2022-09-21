<?php

namespace App\Enums;

use App\Actions\EnumUtilsTrait;

enum AnchorTypesEnum: string
{
    use EnumUtilsTrait;

    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case DANGER = 'danger';
}
