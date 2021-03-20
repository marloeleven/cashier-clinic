<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->string('reference_number');
            $table->string('cashier_id');
            $table->integer('discount_type_lookup_table_id');
            $table->float('discount_value');
            $table->float('original_amount');
            $table->float('discounted_amount');
            $table->float('total');
            $table->string('attending_physician');
            $table->string('comment');
            $table->boolean('senior_citizen_discount')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->integer('cancelled_by_id')->default(0);
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
        Schema::dropIfExists('patient_records');
    }
}
