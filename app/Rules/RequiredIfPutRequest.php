<?php

namespace App\Rules;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\RequiredIf;

/** Use this rule to require a field on a PUT request, and leave it optional/not required on a PATCH request */
class RequiredIfPutRequest extends RequiredIf
{
    /** @var callable|bool */
    public $condition;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->condition = strtolower($request->method()) === 'put';

        parent::__construct($this->condition);
    }
}
