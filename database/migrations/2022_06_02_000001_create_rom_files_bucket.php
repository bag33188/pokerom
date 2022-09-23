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
                $collection->integer('chunkSize');
                $collection->string('filename', MAX_ROM_FILENAME_LENGTH);
                $collection->bigInteger('length', false, true);
                $collection->dateTime('uploadDate');
                $collection->char('md5', MD5_HASH_LENGTH);
            });
            Schema::connection($this->connection)->create('rom.chunks', function (Blueprint $collection) {
                $collection->index(
                    columns: ['files_id', 'n'],
                    name: 'files_id_1_n_1',
                    options: ['unique' => true]
                );
                $collection->integer('n', false, true);
                $collection->binary('data');
                $collection->char('files_id', OBJECT_ID_LENGTH);
                $collection->foreign('files_id')->references('_id')->on('rom.files');
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

            # Schema::connection($this->connection)
            #     ->table('rom.files', function (Blueprint $collection) {
            #         $collection->drop();
            #     });

            # Schema::connection($this->connection)
            #     ->table('rom.chunks', function (Blueprint $collection) {
            #         $collection->drop();
            #     });
        }
    }
};