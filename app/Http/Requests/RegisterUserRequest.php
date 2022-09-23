<?php

namespace App\Http\Requests;

use App\Rules\MaxLengthRule;
use App\Rules\MinLengthRule;
use Hash;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', new MinLengthRule(MIN_USER_NAME_LENGTH), new MaxLengthRule(MAX_USER_NAME_LENGTH)],
            'email' => ['required', 'string', 'email', 'unique:users', new MaxLengthRule(MAX_USER_EMAIL_LENGTH)],
            'password' => ['required', 'string', 'confirmed', new MinLengthRule(MIN_USER_PASSWORD_LENGTH), new MaxLengthRule(MAX_USER_PASSWORD_LENGTH)],
        ];
    }

    // https://stackoverflow.com/a/68647440
    protected function passedValidation(): void
    {
        $this->merge([
            'password' => Hash::make($this->string('password'))
        ]);
    }
}
