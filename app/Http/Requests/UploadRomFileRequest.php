<?php

namespace App\Http\Requests;

use App\Models\RomFile;
use App\Rules\RomFilenameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;

class UploadRomFileRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return $this->user()->can('create', RomFile::class);
    }

    protected function prepareForValidation(): void
    {
        $romFilename = $this->str('rom_filename');
        RomFile::normalizeRomFilename($romFilename);

        $this->merge(['rom_filename' => $romFilename]);
    }

    public function rules(): array
    {
        return array(
            'rom_filename' => [
                'required',
                'string',
                new RomFilenameRule,
                Rule::unique($this->romFilesCollection(App::make(RomFile::class)), 'filename')
            ],
        );
    }

    private function romFilesCollection(RomFile $romFile): string
    {
        return "{$romFile->getConnectionName()}.{$romFile->getTable()}";
    }
}
