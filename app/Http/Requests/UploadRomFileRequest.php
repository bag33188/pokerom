<?php

namespace App\Http\Requests;

use App\Models\RomFile;
use App\Rules\RomFilenameRule;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;
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
                Rule::unique($this->romFilesCollection(), 'filename')
            ]
        );
    }

    public function messages(): array
    {
        return [
            'rom_filename.unique' => 'A ROM file with this name already exists.'
        ];
    }

    private function romFilesCollection(): string
    {
        $this->setContainer(Container::getInstance());
        try {
            $romFile = $this->container->make(RomFile::class);
            return "{$romFile->getConnectionName()}.{$romFile->getTable()}";
        } catch (BindingResolutionException $e) {
            # $e->getMessage();
            return RomFile::class;
        }
    }
}
