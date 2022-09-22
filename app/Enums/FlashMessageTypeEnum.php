<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum FlashMessageTypeEnum: string
{
    use EnumMethods {
        names as public;
    }

    case SUCCESS = 'success'; // green
    case WARNING = 'warning'; // yellow
    case ERROR = 'error'; // red
    case NOTIFICATION = 'notification'; // blue
    case DEFAULT = 'default'; // grey
}
