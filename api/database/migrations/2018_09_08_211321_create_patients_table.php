<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable(true);
            $table->string('last_name', 100);
            $table->string('contact_number', 15)->nullable(true);
            $table->enum('gender', ['male', 'female']);
            $table->string('picture', 100);
            $table->date('birth_date');
            $table->integer('idc_type_lookup_table_id');
            $table->string('idc_number', 50);
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
        Schema::dropIfExists('patients');
    }
}
