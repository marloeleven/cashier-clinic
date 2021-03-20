<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model {

    protected $fillable = [
        'procedure_type_categories_id',
        'name',
        'details',
        'amount'
    ];

    public static function fetchAll() {
        return Procedure::with(['procedure_type_category'])->get();
    }

    public static function fetch($id) {
        return Procedure::with(['procedure_type_category'])->find($id);
    }

    public function procedure_type_category() {
        return $this->hasOne('App\ProcedureTypeCategory', 'id', 'procedure_type_categories_id')
                    ->select(['id', 'procedure_type', 'name', 'alias', 'index', 'report_type']);
    }
}
