<?php

namespace App\Http\Requests;

use App\Models\RomFile;
use App\Rules\RomFilenameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadRomFileRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    protected function prepareForValidation()
    {
        $splitRomFilename = explode('.', $this->string('rom_filename'), 2);

        $this->merge([
            'rom_filename' => $splitRomFilename[0] . '.' . strtolower($splitRomFilename[1]),
        ]);
    }

    public function rules(): array
    {
        return [
            'rom_filename' => ['required', 'string', new RomFilenameRule, Rule::unique(RomFile::class, 'filename')],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', RomFile::class);
    }
}
