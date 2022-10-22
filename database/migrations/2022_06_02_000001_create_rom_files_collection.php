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

            $metadataRomTypes = collect(ROMFILE_TYPES)->map(
                fn(string $romType): string => str_replace(_FULLSTOP, '', $romType)
            )->toArray();

            Schema::create('rom.files', function (Blueprint $collection) use ($metadataRomTypes) {
                $collection->unsignedMediumInteger('chunkSize');
                $collection->string('filename', MAX_ROM_FILENAME_LENGTH);
                $collection->unsignedBigInteger('length');
                $collection->dateTime('uploadDate');
                $collection->char('md5', MD5_HASH_LENGTH);
                $collection->json('metadata');
                $collection->enum('metadata.romType', $metadataRomTypes);
                $collection->string('metadata.contentType', METADATA_CONTENT_TYPE_LENGTH);

                $collection->index(
                    columns: ['length', 'filename'],
                    name: 'length_1_filename_1', // length: ascending, filename: ascending
                    options: [
                        'unique' => true,
                        'partialFilterExpression' => [
                            'filename' => ['$exists' => true]
                        ]
                    ]
                );

                // TODO: find a way to implement aggregate text search index
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
