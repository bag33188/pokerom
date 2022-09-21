<?php

use App\Enums\ForeignKeyConstraintOptionEnum as ConstraintOption;
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
        Schema::create('sessions', function (Blueprint $table) {
            #$table->string('id')->primary();
            #$table->foreignId('user_id')->nullable()->index();
            #$table->string('ip_address', 45)->nullable();
            #$table->text('user_agent')->nullable();
            #$table->longText('payload');
            #$table->integer('last_activity')->index();
            $table->char('id', SESSION_ID_LENGTH)->primary();
            $table->foreignId('user_id')->nullable()->index()->references('id')->on('users')->onDelete(ConstraintOption::NO_ACTION->value)->onUpdate(ConstraintOption::NO_ACTION->value);
            $table->string('ip_address', IP_ADDRESS_LENGTH)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
