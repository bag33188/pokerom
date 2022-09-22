<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait as EnumMethods;

enum FlashMessageTypeEnum: string
{
    use EnumMethods;

    case SUCCESS = 'success';
    case ERROR = 'error';
    case NOTIFICATION = 'notification';
    case DEFAULT = 'default';
}
