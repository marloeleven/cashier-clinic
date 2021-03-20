<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Patient extends Model {

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'picture',
        'contact_number',
        'birth_date',
        'idc_type_lookup_table_id',
        'idc_number',
    ];

    protected $appends = ['full_name'];

    public static function fetchAll($searchFor, $page, $limit, $disabled, $sortBy, $direction = 'asc') {
        $patient = Patient::where('disabled', $disabled)->with(['idc_type']);

        if ($searchFor) {
            $patient = $patient->where(function($query) use ($searchFor) {
                $query->orWhereRaw("
                    CONCAT_WS(' ', `first_name`, `middle_name`, `last_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `first_name`, `last_name`, `middle_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `middle_name`, `last_name`, `first_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `middle_name`, `first_name`, `last_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `last_name`, `middle_name`, `first_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `last_name`, `first_name`, `middle_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `first_name`, `middle_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `first_name`, `last_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `middle_name`, `first_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `middle_name`, `last_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `last_name`, `first_name`) like '%{$searchFor}%' OR
                    CONCAT_WS(' ', `last_name`, `middle_name`) like '%{$searchFor}%'
                ")
                ->orWhere('contact_number', 'like', $searchFor)
                ->orWhere('birth_date', 'like', $searchFor); 
            });
        }

        $offset = $page * $limit;

        if ($sortBy && $direction) {
            $patient = $patient->orderBy($sortBy, $direction);
        }
        
        return [
                "total" => $patient->count(),
                "data" => $patient->offset($offset)->limit($limit)->get(),
                'searchFor' => $searchFor,
                'sql' => $patient->offset($offset)->limit($limit)->toSql(),
                'disabled' => $disabled
                ];
    }

    public static function fetch($id) {
        return Patient::with(['idc_type'])->find($id);
    }

    // Relationships
    public function records() {
        return $this->hasMany('App\PatientRecords');
    }

    public function idc_type() {
        return $this->hasOne('App\LookupTable', 'id', 'idc_type_lookup_table_id')
                    ->select(['id', 'name']);
    }

    public function getFullNameAttribute() {
        $first_name = $this->attributes['first_name'];
        $middle_initial = substr($this->attributes['middle_name'], 0, 1);
        $middle_initial = $middle_initial ? "{$middle_initial}." : '';
        $last_name = $this->attributes['last_name'];
        return "{$first_name} {$middle_initial} {$last_name}";
    }

    public static function isSenior($birth_date) {
        $age = date("Y") - date("Y", strtotime($birth_date));
        return $age >= 60;
    }
}
