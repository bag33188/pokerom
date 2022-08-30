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
        Schema::create('users', function (Blueprint $table) {
//            $table->id();
//            $table->string('name');
//            $table->string('email')->unique();
//            $table->timestamp('email_verified_at')->nullable();
//            $table->string('password');
//            $table->enum('role', USER_ROLES)->default(USER_ROLES[1]);
//            $table->rememberToken();
//            $table->foreignId('current_team_id')->nullable();
//            $table->string('profile_photo_path', 2048)->nullable();
//            $table->timestamps();
            $table->id();
            $table->string('name', MAX_USER_NAME_LENGTH);
            $table->string('email', MAX_USER_EMAIL_LENGTH)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->char('password', BCRYPT_PASSWORD_LENGTH);
            $table->enum('role', USER_ROLES)->default(USER_ROLES[1]);
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', PROFILE_PHOTO_URI_LENGTH)->nullable();
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
        Schema::dropIfExists('users');
    }
};
