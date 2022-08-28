<?php

namespace App\Http\Requests;

use App\Models\Rom;
use App\Rules\MaxLengthRule;
use App\Rules\MaxSizeRule;
use App\Rules\MinLengthRule;
use App\Rules\MinSizeRule;
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
    public function authorize(): bool
    {
        return $this->user()->can('create', Rom::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rom_name' => ['required', 'string', new MinLengthRule(MIN_ROM_NAME_LENGTH), new MaxLengthRule(MAX_ROM_NAME_LENGTH), new RomNameRule, 'unique:roms'],
            'rom_size' => ['required', 'integer', new MinSizeRule(MIN_ROM_SIZE), new MaxSizeRule(MAX_ROM_SIZE)],
            'rom_type' => ['required', 'string', new MinLengthRule(MIN_ROM_TYPE_LENGTH), new MaxLengthRule(MAX_ROM_TYPE_LENGTH), new RomTypeRule],
        ];
    }
}
