<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [

    ];

    /*
    * Define The Role User relationship (Many to Many)
    */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
