<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class LookupTable extends Model {

    protected $fillable = [
        'type',
        'name',
        'details',
        'value'
    ];

    protected $table = 'lookup_table';

    public function procedure()
    {
    	return $this->belongsTo('App\Procedure');
    }

}
