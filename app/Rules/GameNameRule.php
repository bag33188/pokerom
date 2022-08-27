<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GameNameRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value)
    {
        return preg_match(GAME_NAME_PATTERN, $value);
    }

    public function message()
    {
        return "Invalid `:attribute`. Game Name must start with the word 'Pokemon'.";
    }
}
