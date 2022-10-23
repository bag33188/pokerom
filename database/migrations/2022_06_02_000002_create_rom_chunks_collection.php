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
        if (config('database.connections.mongodb.gridfs.allowMigrations', false) === true) {
            Schema::create('rom.chunks', function (Blueprint $collection) {
                $filesIdIndexName = 'files_id_1_n_1'; // files_id: ascending, n: ascending

                $collection->unsignedInteger('n');
                $collection->binary('data');
                $collection->char('files_id', OBJECT_ID_LENGTH);

                $collection->foreign('files_id', "${filesIdIndexName}_foreign")
                    ->references('_id')
                    ->on('rom.files');

                $collection->index(
                    columns: ['files_id', 'n'],
                    name: $filesIdIndexName,
                    options: ['unique' => true]
                );
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
