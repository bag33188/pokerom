<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GameRegionRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value): bool
    {
        return in_array(strtolower($value), REGIONS);
    }

    public function message(): string
    {
        $validGameRegionsStr = join(', ', REGIONS);
        return "Invalid `:attribute`. Game Region must be one of: `" . $validGameRegionsStr . "`.";
    }
}
