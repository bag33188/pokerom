<?php

namespace App\Http\Requests;

use App\Models\RomFile;
use App\Rules\MaxLengthRule;
use App\Rules\MinLengthRule;
use App\Rules\RomFilenameRule;
use Exception;
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
        $romFilesCollection = $this->getRomFilesCollectionReference();
        return [
            'rom_filename' => [
                'required',
                'string',
                new MinLengthRule(MIN_ROM_FILENAME_LENGTH),
                new MaxLengthRule(MAX_ROM_FILENAME_LENGTH),
                new RomFilenameRule,
                Rule::unique($romFilesCollection, 'filename')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'rom_filename.unique' => 'A ROM file with this name already exists.'
        ];
    }

    /**
     * Retrieves the **MongoDB-GridFS DSN reference** for the _`rom.files`_ collection.
     *
     * @return string
     */
    private function getRomFilesCollectionReference(): string
    {
        # $this->setContainer(\Illuminate\Container\Container::getInstance());

        try {
            $romFile = $this->container->make(RomFile::class);
            $romFilesCollectionReference = "{$romFile->getConnectionName()}.{$romFile->getTable()}";
        } catch (BindingResolutionException $e) {
            $romFilesCollectionReference = RomFile::class;
        } catch (Exception $e) {
            $romFilesCollectionReference = 'undefined';
        } finally {
            return $romFilesCollectionReference;
        }
    }
}
