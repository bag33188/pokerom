<?php

namespace App\Enums;

use App\Actions\EnumMethodsTrait;

enum AlertTypeEnum: string
{
    use EnumMethodsTrait;

    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case DEFAULT = 'default';
}
