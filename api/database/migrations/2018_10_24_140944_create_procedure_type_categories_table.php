<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureTypeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedure_type_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('procedure_type');
            $table->string('name', 100);
            $table->string('alias', 50);
            $table->integer('index');
            $table->string('report_type')->default('generic');
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
        Schema::dropIfExists('procedure_type_categories');
    }
}
