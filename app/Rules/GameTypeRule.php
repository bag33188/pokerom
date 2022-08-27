<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GameTypeRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return in_array(strtolower($value), GAME_TYPES);
    }

    public function message()
    {
        $validGameTypesStr = implode(', ', GAME_TYPES);
        return "Invalid `:attribute`. Game Type must be one of: `" . $validGameTypesStr . "`.";
    }
}
