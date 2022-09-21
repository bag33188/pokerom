<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait;

enum AnchorButtonTypeEnum: string
{
    use EnumMethodsTrait;

    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case DANGER = 'danger';
}
