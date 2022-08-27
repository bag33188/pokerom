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

    /**
     * Execute migration within transaction IF AVAILABLE
     *
     * @var bool
     */
    public $withinTransaction = true;

    protected static bool $_ALLOW_MIGRATIONS = false;

    private const COLLECTION_NAME = 'rom_files';

    private static function getFileTypeEnumValues(): array
    {
        $fileTypes = [];
        foreach (ROM_FILE_EXTENSIONS as $fileType) $fileTypes[] = str_replace('.', '', $fileType);
        return $fileTypes;
    }

    public function up(): void
    {
        if (self::$_ALLOW_MIGRATIONS === true) {
            $filename_length = MAX_ROM_FILENAME_LENGTH - 4;

            Schema::connection($this->connection)->create(self::COLLECTION_NAME, function (Blueprint $collection) use ($filename_length) {
                // compound index
                // filename and filetype fields are unique if they already exist together on the same document
                // at the time of querying
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
                $collection->enum('filetype', self::getFileTypeEnumValues());
                $collection->unsignedBigInteger('filesize', autoIncrement: false);
            });
        }
    }

    public function down(): void
    {
        if (self::$_ALLOW_MIGRATIONS === true) {
            Schema::dropIfExists(self::COLLECTION_NAME);

            # Schema::connection($this->connection)
            #     ->table(self::COLLECTION_NAME, function (Blueprint $collection) {
            #         $collection->drop();
            #     });
        }
    }
};
