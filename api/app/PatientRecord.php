<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use DB;

class PatientRecord extends Model {

    protected $fillable = [
        'patient_id',
        'reference_number',
        'cashier_id',
        'discount_type_lookup_table_id',
        'discount_value',
        'original_amount',
        'discounted_amount',
        'total',
    ];

    public static function fetchAll($patientId) {
        return PatientRecord::with(['patient', 'cashier', 'cancelled', 'discount'])->wherePatientId($patientId)->get();
    }    

    public static function fetch($id) {
        return PatientRecord::with([
            'patient',
            'patient.idc_type',
            'cashier',
            'cancelled',
            'procedures',
            'procedures.procedure',
            'procedures.procedure.procedure_type_category'
        ])->find($id);
    }


    public static function getProcedures($id) {
        return PatientRecord::with([
            'procedures',
            'procedures.procedure',
            'procedures.procedure.procedure_type_category',
          ])->find($id);
    }

    public function procedures() {
        return $this->hasMany('App\PatientRecordProcedure', 'patient_record_id', 'id');
    }

    public function patient() {
        return $this->hasOne('App\Patient', 'id', 'patient_id')
                    ->select([
                        'id',
                        'first_name',
                        'middle_name',
                        'last_name',
                        'birth_date',
                        'gender',
                        'idc_type_lookup_table_id',
                        'contact_number',
                        'idc_number'
                    ]);
    }

    public function cashier() {
        return $this->hasOne('App\User', 'id', 'cashier_id')
                    ->select(['id', 'first_name', 'middle_name', 'last_name']);
    }

    public function cancelled() {
        return $this->hasOne('App\User', 'id', 'cancelled_by_id')
                    ->select(['id', 'first_name', 'middle_name', 'last_name']);
    }

    public function discount() {
        return $this->hasOne('App\LookupTable', 'id', 'discount_type_lookup_table_id')->select(['id', 'name', 'amount']);
    }

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }

}
