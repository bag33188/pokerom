<?php

namespace App\Http\Requests;

use App\Rules\MaxLengthRule;
use App\Rules\MinLengthRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    private readonly string $routeParamName;

    public function authorize(): bool
    {
        $this->routeParamName = $this->routeIs('api.*') ? 'userId' : 'user';

        return $this->user()->can('update', auth()->user());
    }

    public function rules(): array
    {
        return [
            'name' => [Rule::requiredIf($this->isMethod(self::METHOD_PUT)), 'string', new MinLengthRule(MIN_USER_NAME_LENGTH), new MaxLengthRule(MAX_USER_NAME_LENGTH)],
            'email' => [Rule::requiredIf($this->isMethod(self::METHOD_PUT)), 'string', 'email', new MaxLengthRule(MAX_USER_EMAIL_LENGTH), Rule::unique('users', 'email')->ignore($this->route($this->routeParamName), 'id')],
            'password' => [Rule::requiredIf($this->isMethod(self::METHOD_PUT)), 'string', 'confirmed', new MinLengthRule(MIN_USER_PASSWORD_LENGTH), new MaxLengthRule(MAX_USER_PASSWORD_LENGTH)],
        ];
    }

    // https://stackoverflow.com/a/68647440
    protected function passedValidation(): void
    {
        if ($this->exists('password') && $this->exists('password_confirmation')) {
            $this->merge(['password' => bcrypt($this->str('password'))]);
        }
    }
}
