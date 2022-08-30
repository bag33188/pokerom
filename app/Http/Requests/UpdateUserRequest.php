<?php

namespace App\Http\Requests;

use App\Rules\MaxLengthRule;
use App\Rules\MinLengthRule;
use App\Rules\RequiredIfPutRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    function __construct(private readonly RequiredIfPutRequest $requiredIfPutRequest)
    {
        parent::__construct();
    }

    public function rules(): array
    {
        $routeParamName = $this->is('api/*') ? 'userId' : 'user';
        return [
            'name' => [$this->requiredIfPutRequest, 'string', new MinLengthRule(MIN_USER_NAME_LENGTH), new MaxLengthRule(MAX_USER_NAME_LENGTH)],
            'email' => [$this->requiredIfPutRequest, 'string', 'email', 'max:' . MAX_USER_EMAIL_LENGTH, Rule::unique('users')->ignore($this->route($routeParamName))],
            'password' => [$this->requiredIfPutRequest, 'string', 'confirmed', new MinLengthRule(MIN_USER_PASSWORD_LENGTH), new MaxLengthRule(MAX_USER_PASSWORD_LENGTH)],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('update', auth()->user());
    }
}
