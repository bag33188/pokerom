<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\MaxLengthRule;
use App\Rules\MinLengthRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class LoginUserRequest extends FormRequest
{
    private readonly ?User $userFromRequest;

    public function authorize(): bool
    {
        $this->userFromRequest = User::where('email', '=', $this->string('email'))->firstOrFail();
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', new MaxLengthRule(MAX_USER_EMAIL_LENGTH)],
            'password' => ['required', 'string', new MinLengthRule(MIN_USER_PASSWORD_LENGTH), new MaxLengthRule(MAX_USER_PASSWORD_LENGTH)],
        ];
    }


    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        // checks user current password
        // before making changes
        $validator->after(function (Validator $validator) {
            if (!Hash::check($this->string('password'), $this->userFromRequest->getAttributeValue('password'))) {
                $validator->errors()->add('password', 'The password you entered is incorrect.');
            }
        });
    }

    public function passedValidation(): void
    {
        $this->merge([
            'user' => $this->userFromRequest
        ]);
    }
}
