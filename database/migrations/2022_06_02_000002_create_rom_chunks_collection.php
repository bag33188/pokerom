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

    public function up(): void
    {
        /*! don't use down methods as it could overwrite current files in db */
        if (config('database.connections.mongodb.gridfs.allowMigrations', false) === true) {
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
                $collection->foreign('files_id', $filesIdIndexName)
                    ->references('_id')
                    ->on('rom.files');
            });
        }
    }

    public function down(): void
    {
        if (config('database.connections.mongodb.gridfs.allowMigrations', false) === true) {
            Schema::dropIfExists('rom.chunks');
        }
    }
};
