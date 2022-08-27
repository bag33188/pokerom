<?php

namespace App\Http\Requests;

use App\Models\Rom;
use App\Rules\RomNameRule;
use App\Rules\RomTypeRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Rom::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'rom_name' => ['required', 'string', 'max:3', 'max:28', new RomNameRule()],
            'rom_size' => ['required', 'integer', 'min:1020', 'max:17825792'],
            'rom_type' => ['required', 'string', 'min:2', 'max:4', new RomTypeRule()],
        ];
    }
}
