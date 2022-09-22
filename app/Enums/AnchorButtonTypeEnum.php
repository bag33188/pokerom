<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum AnchorButtonTypeEnum: string
{
    use EnumMethods;

    case PRIMARY = 'primary'; // black
    case SECONDARY = 'secondary'; // grey
    case DANGER = 'danger'; // red
    case INFO = 'info'; // blue
    case CAUTION = 'caution'; // yellow
    case OK = 'ok'; // green
}
