<?php

use App\Enums\UserRoleEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', MAX_USER_NAME_LENGTH);
            $table->string('email', MAX_USER_EMAIL_LENGTH)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->char('password', BCRYPT_PASSWORD_LENGTH);
            $table->enum('role', USER_ROLES)->default(UserRoleEnum::DEFAULT->value);
            $table->rememberToken();
            $table->mediumInteger('current_team_id', false, true)->nullable();
            $table->string('profile_photo_path', PROFILE_PHOTO_URI_LENGTH)->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->char('remember_token', REMEMBER_TOKEN_LENGTH)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::table('users', function (Blueprint $table) {
            $table->string('remember_token', 100)->change();
        });
    }
};
