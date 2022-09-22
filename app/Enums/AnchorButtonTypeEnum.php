<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum AnchorButtonTypeEnum: string
{
    use EnumMethods;

    case PRIMARY = 'primary';
    case SECONDARY = 'secondary';
    case DANGER = 'danger';
    case INFO = 'info';
    case CAUTION = 'caution';
    case OK = 'ok';
}
