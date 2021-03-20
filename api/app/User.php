<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'type',
        'first_name',
        'middle_name',
        'last_name',
        'gender'
    ];

    protected $appends = ['full_name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected static function selection() {
        return User::select([
            'id',
            'username',
            'email',
            'first_name',
            'middle_name',
            'last_name',
            'gender',
            'picture',
            'type',
            'disabled'
          ]);
    }

    public static function fetchAll() {
        return User::selection()->get();
    }

    public static function fetch($id) {
        return User::selection()->find($id);
    }

    public function getFullNameAttribute() {
        $first_name = $this->attributes['first_name'];
        $middle_initial = substr($this->attributes['middle_name'], 0, 1); 
        $middle_initial = $middle_initial ? "{$middle_initial}." : '';
        $last_name = $this->attributes['last_name']; 
        return "{$first_name} {$middle_initial} {$last_name}";
    }
}
