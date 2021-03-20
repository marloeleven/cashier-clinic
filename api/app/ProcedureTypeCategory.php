<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProcedureTypeCategory extends Model {

    public $table = "procedure_type_categories";

    protected $fillable = [
        'procedure_type',
        'alias',
        'name',
        'report_type',
        'index'
    ];

    public static function selection() {
        return ProcedureTypeCategory::select([
            'id',
            'procedure_type',
            'name',
            'alias',
            'report_type',
            'index',
            'disabled',
        ]);
    }

    public static function fetchAll() {
        return ProcedureTypeCategory::selection()->get();
    }

    public static function fetchType($type) {
      return ProcedureTypeCategory::selection()->where('procedure_type', $type)->get();
    }

    public static function fetch($id) {
        return ProcedureTypeCategory::selection()->find($id);
    }

    public static function getType($array, $type) {
      return array_reduce($array, function($arr, $value) use ($type) {
        if ($value['procedure_type'] === $type) {
            array_push($arr, $value);
        }

        return $arr;
      }, []);
    }

    public function procedures() {
        return $this->hasMany('App\Procedure', 'procedure_type_categories_id')->whereDisabled(0)->select(['id', 'procedure_type_categories_id', 'name', 'details', 'amount', 'sort'])->orderBy('sort');

    }

    // SCOPE

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('procedure_type', 'ASC')->orderBy('name', 'ASC');
        });
    }
}
