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
use Illuminate\Validation\Rule;

class UpdateRomRequest extends FormRequest
{
    private readonly string $routeParamName;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $this->routeParamName = $this->routeIs('api.*') ? 'romId' : 'rom';

        $rom = Rom::find($this->route($this->routeParamName));
        return $this->user()->can('update', $rom);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rom_name' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_ROM_NAME_LENGTH), new MaxLengthRule(MAX_ROM_NAME_LENGTH), new RomNameRule(), Rule::unique('roms', 'rom_name')->ignore($this->route($this->routeParamName))],
            'rom_size' => [Rule::requiredIf($this->isMethod('PUT')), 'integer', new MinSizeRule(MIN_ROM_SIZE), new MaxSizeRule(MAX_ROM_SIZE)],
            'rom_type' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_ROM_TYPE_LENGTH), new MaxLengthRule(MAX_ROM_TYPE_LENGTH), new RomTypeRule()],
        ];
    }
}
