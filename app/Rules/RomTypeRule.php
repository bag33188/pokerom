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
        return in_array($value, array('gb', 'gbc', 'gba', 'nds', '3ds', 'xci'));
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
