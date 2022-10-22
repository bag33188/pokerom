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

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (config('database.connections.mongodb.gridfs.allowMigrations', false) === true) {
            Schema::connection($this->connection)->create('rom.files', function (Blueprint $collection) {
                $collection->index(
                    columns: ['length', 'filename'],
                    name: 'length_1_filename_1',
                    options: [
                        'unique' => true,
                        'partialFilterExpression' => [
                            'filename' => ['$exists' => true]
                        ]
                    ]
                );
                $collection->unsignedMediumInteger('chunkSize');
                $collection->string('filename', MAX_ROM_FILENAME_LENGTH);
                $collection->unsignedBigInteger('length');
                $collection->dateTime('uploadDate');
                $collection->char('md5', MD5_HASH_LENGTH);
                $collection->enum('metadata.romType', collect(ROMFILE_TYPES)->map(
                    fn(string $type): string => str_replace('.', '', $type))->toArray()
                );
                $collection->string('metadata.contentType', 36);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (config('database.connections.mongodb.gridfs.allowMigrations', false) === true) {
            Schema::dropIfExists('rom.files');
        }
    }
};
