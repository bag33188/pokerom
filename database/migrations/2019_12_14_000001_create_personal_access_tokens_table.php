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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name', PERSONAL_ACCESS_TOKEN_NAME_LENGTH);
            $table->char('token', PERSONAL_ACCESS_TOKEN_LENGTH)->unique();
            $table->text('abilities')->nullable();
            $table->foreign('tokenable_id', 'personal_access_tokens_tokenable_id_foreign') // <-- part of `morphs('tokenable')`
            ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('tokenable_type', PERSONAL_ACCESS_TOKEN_TYPE_LENGTH)->change(); // <-- part of `morphs('tokenable')`
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('tokenable_type', 255)->change(); // <-- part of `morphs('tokenable')`
        });
        Schema::dropIfExists('personal_access_tokens');
    }
};
