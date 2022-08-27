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

    private const COLLECTION_NAME = 'rom.chunks';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (self::$_ALLOW_MIGRATIONS === true) {
            Schema::connection($this->connection)->create(self::COLLECTION_NAME, function (Blueprint $collection) {
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
        if (self::$_ALLOW_MIGRATIONS === true) {
            Schema::dropIfExists(self::COLLECTION_NAME);

            # Schema::connection($this->connection)
            #     ->table(self::COLLECTION_NAME, function (Blueprint $collection) {
            #         $collection->drop();
            #     });
        }
    }
};
