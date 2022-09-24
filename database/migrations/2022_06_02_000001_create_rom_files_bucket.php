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


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        /*!! never execute. current data is intended to be permanent !!*/
        if (self::_ALLOW_MIGRATIONS_ === true) {
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
                /*! note: metadata `field-object` (of this collection) cannot be represented in this migration */
            });
            Schema::connection($this->connection)->create('rom.chunks', function (Blueprint $collection) {
                $filesIdIndexName = 'files_id_1_n_1';
                $collection->index(
                    columns: ['files_id', 'n'],
                    name: $filesIdIndexName,
                    options: ['unique' => true]
                );
                $collection->unsignedInteger('n');
                $collection->binary('data');
                $collection->char('files_id', OBJECT_ID_LENGTH);
                $collection->foreign('files_id', $filesIdIndexName)->references('_id')->on('rom.files');
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
        /*! don't use down methods as it could overwrite current files in db */
        if (self::_ALLOW_MIGRATIONS_ === true) {
            Schema::dropIfExists('rom.files');
            Schema::dropIfExists('rom.chunks');
        }
    }
};
