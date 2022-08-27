<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RomTypeRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return in_array($value, ROM_TYPES);
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
