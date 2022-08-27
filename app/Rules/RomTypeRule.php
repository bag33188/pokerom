<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RomTypeRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value): bool
    {
        return in_array($value, ROM_TYPES);
    }

    public function message(): string
    {
        return 'The validation error message.';
    }
}
