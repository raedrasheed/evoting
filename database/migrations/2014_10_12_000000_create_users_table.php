<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('outer_id')->nullable();
            $table->string('name');
			$table->string('photo')->nullable();
			$table->string('username')->unique();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
			$table->string('facebook_id')->nullable();
			$table->integer('role')->nullable();
			$table->boolean('is_active')->nullable()->default(true);
			$table->string('description')->nullable();
			$table->string('mobile')->nullable();
			$table->boolean('voted')->nullable()->default(false);
            $table->rememberToken();
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
}
