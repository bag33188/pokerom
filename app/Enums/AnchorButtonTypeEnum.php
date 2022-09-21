<?php

namespace App\Enums;

use App\Actions\EnumUtilsTrait;

enum AnchorButtonTypeEnum: string
{
    use EnumUtilsTrait;

    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case DANGER = 'danger';
}
