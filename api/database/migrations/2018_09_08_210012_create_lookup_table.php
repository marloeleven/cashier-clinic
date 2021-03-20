<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lookup_table', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50);
            $table->string('name', 60);
            $table->string('details', 200);
            $table->integer('index')->default(0);
            $table->float('amount', 8, 2)->default(0);
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
        Schema::dropIfExists('lookup_table');
    }
}
