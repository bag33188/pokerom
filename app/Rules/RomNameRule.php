<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RomNameRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value): bool|int
    {
        return preg_match(ROM_NAME_PATTERN, $value);
    }

    public function message(): string
    {
        return "Invalid `:attribute`. Rom Name can only contain words, numbers, underscores, and/or hyphens";
    }
}
