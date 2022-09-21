<?php

namespace App\Enums;

enum AlertTypesEnum: string {
    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
    case DEFAULT = 'default';
}
