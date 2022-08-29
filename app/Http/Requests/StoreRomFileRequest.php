<?php

namespace App\Http\Requests;

use App\Models\RomFile;
use App\Rules\RomFilenameRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRomFileRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    public function rules(): array
    {
        return [
            'rom_filename' => ['required', 'string', new RomFilenameRule],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', RomFile::class);
    }
}
