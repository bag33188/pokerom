<?php

namespace App\Http\Requests;

use App\Models\RomFile;
use App\Rules\RomFilenameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadRomFileRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
