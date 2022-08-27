<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxSizeRule implements Rule
{
    private readonly int $size;


    public function __construct(int $size)
    {
        $this->size = $size;
    }

    public function passes($attribute, $value): bool
    {
        return $value <= $this->size;
    }

    public function message(): string
    {
        return "The :attribute must not be greater than $this->size.";
    }
}
