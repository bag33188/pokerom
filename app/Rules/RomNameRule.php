<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RomNameRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return preg_match("/^[\w\d_\-]+$/i", $value);
    }

    public function message()
    {
        return "Invalid `:attribute`. Rom Name can only contain words, numbers, underscores, and/or hyphens";
    }
}
