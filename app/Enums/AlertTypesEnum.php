<?php

namespace App\Enums;

use App\Actions\EnumUtilsTrait;

enum AlertTypesEnum: string
{
    use EnumUtilsTrait;

    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case DEFAULT = 'default';
}
