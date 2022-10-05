<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum AlertTypeEnum: string
{
    use EnumMethods;

    case SUCCESS = 'success';   // green
    case ERROR = 'error';       // red
    case WARNING = 'warning';   // yellow
    case MESSAGE = 'message';   // blue
    case NORMAL = 'normal';     // grey
}
