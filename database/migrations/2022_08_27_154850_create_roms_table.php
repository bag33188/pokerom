<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'mysql';
    public $withinTransaction = true;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roms', function (Blueprint $table) {
            $table->id();
            $table->string('rom_name', MAX_ROM_NAME_LENGTH)->unique();
            $table->bigInteger('game_id')->unsigned()->nullable()->unique();
            $table->char('file_id', OBJECT_ID_LENGTH)->nullable()->unique()
                ->comment('Points to a single document within GridFS. Reference Path: `mongodb.pokerom_files.rom.files._id`');
            $table->integer('rom_size')->unsigned()->default(MIN_ROM_SIZE);
            $table->enum('rom_type', ROM_TYPES);
            $table->boolean('has_game')->default(false);
            $table->boolean('has_file')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roms');
    }
};
