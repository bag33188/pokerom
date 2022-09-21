<?php

namespace App\Http\Requests;

use App\Rules\MaxLengthRule;
use App\Rules\MinLengthRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    private string $routeParamName;

    function __construct()
    {
        parent::__construct();

        $this->routeParamName = $this->routeIs('api.*') ? 'userId' : 'user';
    }

    public function rules(): array
    {
        return [
            'name' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_USER_NAME_LENGTH), new MaxLengthRule(MAX_USER_NAME_LENGTH)],
            'email' => [Rule::requiredIf($this->isMethod('PUT')), 'string', 'email', 'max:' . MAX_USER_EMAIL_LENGTH, Rule::unique('users', 'email')->ignore($this->route($this->routeParamName))],
            'password' => [Rule::requiredIf($this->isMethod('PUT')), 'string', 'confirmed', new MinLengthRule(MIN_USER_PASSWORD_LENGTH), new MaxLengthRule(MAX_USER_PASSWORD_LENGTH)],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('update', auth()->user());
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'password' => bcrypt($this->string('password')),
        ]);
    }
}
