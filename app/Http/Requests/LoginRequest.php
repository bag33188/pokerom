<?php

namespace App\Http\Requests;

use App\Rules\MaxLengthRule;
use App\Rules\MinLengthRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:' . MAX_USER_EMAIL_LENGTH,
            'password' => ['required', 'string', new MinLengthRule(MIN_USER_PASSWORD_LENGTH), new MaxLengthRule(MAX_USER_PASSWORD_LENGTH)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
