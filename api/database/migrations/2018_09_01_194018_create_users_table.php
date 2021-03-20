<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50)->unique();
            $table->string('password', 60);
            $table->string('email', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable(true);
            $table->string('last_name', 100);
            $table->enum('gender', ['male', 'female']);
            $table->string('picture', 100);
            $table->string('token', 60)->nullable(true);
            $table->dateTime('token_expiration')->nullable(true);
            $table->enum('type', ['SUPER_ADMIN', 'ADMIN', 'SUPERVISOR', 'CASHIER'])->default('CASHIER');
            $table->boolean('disabled')->default(false);
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
