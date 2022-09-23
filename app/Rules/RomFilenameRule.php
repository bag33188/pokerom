<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RomFilenameRule implements Rule
{
    public function __construct()
    {
    }

    public function passes($attribute, $value): bool
    {
        return preg_match(ROM_FILENAME_PATTERN, $value);
    }

    public function message(): string
    {
        return 'Invalid ROM Filename.';
    }
}
