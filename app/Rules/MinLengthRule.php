<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinLengthRule implements Rule
{
    private readonly int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }


    public function passes($attribute, $value): bool
    {
        return strlen($value) >= $this->length;
    }

    public function message(): string
    {
        return "The :attribute must be at least {$this->length} characters.";
    }
}
