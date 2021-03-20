<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientRecordProcedure extends Model {
  public function procedure() {
    return $this->hasOne('App\Procedure', 'id', 'procedure_id');
  }

  public function patient_record() {
    return $this->belongsTo('App\PatientRecord', 'patient_record_id')->select([
        'id',
        'patient_id',
        'discount_type_lookup_table_id',
        'reference_number',
        'discount_value',
        'original_amount',
        'discounted_amount',
        'total',
        'attending_physician',
        'senior_citizen_discount',
        'created_at',
        'cashier_id'
      ]);
  }
}
