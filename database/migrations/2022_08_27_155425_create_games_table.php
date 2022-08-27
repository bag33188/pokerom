<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rom_id')->unique()->references('id')->on('roms')->onDelete('cascade')->onUpdate('cascade');
            $table->string('game_name', 40);
            $table->enum('game_type', GAME_TYPES);
            $table->date('date_released');
            $table->tinyInteger('generation')->unsigned();
            $table->enum('region', REGIONS);
            $table->string('slug', 42)->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
};
