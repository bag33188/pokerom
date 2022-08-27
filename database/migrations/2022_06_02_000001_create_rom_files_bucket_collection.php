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

    private const COLLECTION_NAME = 'rom.files';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        /*!! never execute. current data is intended to be permanent !!*/
        if (self::$_ALLOW_MIGRATIONS === true) {
            Schema::connection($this->connection)->create(self::COLLECTION_NAME, function (Blueprint $collection) {
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
        if (self::$_ALLOW_MIGRATIONS === true) {
            Schema::dropIfExists(self::COLLECTION_NAME);

            # Schema::connection($this->connection)
            #     ->table(self::COLLECTION_NAME, function (Blueprint $collection) {
            #         $collection->drop();
            #     });
        }
    }
};
