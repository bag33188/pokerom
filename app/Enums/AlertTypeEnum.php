<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum AlertTypeEnum: string
{
    use EnumMethods;

    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case MESSAGE = 'message';
    case NORMAL = 'normal';
}
