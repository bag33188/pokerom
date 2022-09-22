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
        $romFilename = $this->string('rom_filename');
        RomFile::normalizeRomFilename($romFilename);
        $this->merge([
            'rom_filename' => $romFilename
        ]);
    }

    public function rules(): array
    {
        return [
            'rom_filename' => [
                'required',
                'string',
                new RomFilenameRule,
                Rule::unique(RomFile::class, 'filename'), // <-- works EXACTLY as excepted
            ],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', RomFile::class);
    }
}
