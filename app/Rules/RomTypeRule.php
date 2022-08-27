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
        $validRomTypeStr = implode(', ', ROM_TYPES);
        return "Invalid `:attribute`. Rom Type must be one of: `" . $validRomTypeStr . "`.";
    }
}
