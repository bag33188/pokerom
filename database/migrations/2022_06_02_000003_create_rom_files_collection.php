<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Jenssegers\Mongodb\Schema\Blueprint;

return new class extends Migration {

    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection = 'mongodb';
    public $withinTransaction = true;
    protected const _ALLOW_MIGRATIONS_ = false;

    public function up(): void
    {
        if (self::_ALLOW_MIGRATIONS_ === true) {
            $filename_length = MAX_ROM_FILENAME_LENGTH - 4;
            $rom_filetypes = array_merge(ROM_TYPES, ROMFILE_TYPES);

            Schema::connection($this->connection)->create('rom_files', function (Blueprint $collection) use ($filename_length, $rom_filetypes) {
                $collection->index(
                    columns: ['filename', 'filetype'],
                    name: 'filename_1_filetype_1',
                    options: [
                        'unique' => true,
                        'partialFilterExpression' => [
                            'filename' => ['$exists' => true],
                            'filetype' => ['$exists' => true]
                        ]
                    ]
                );
                $collection->string('filename', $filename_length);
                $collection->enum('filetype', $rom_filetypes);
                $collection->bigInteger('filesize', autoIncrement: false, unsigned: true);
            });
        }
    }

    public function down(): void
    {
        if (self::_ALLOW_MIGRATIONS_ === true) {
            Schema::dropIfExists('rom_files');

            # Schema::connection($this->connection)
            #     ->table('rom_files', function (Blueprint $collection) {
            #         $collection->drop();
            #     });
        }
    }
};
