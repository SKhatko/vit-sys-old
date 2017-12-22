<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientStatus extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'icon'
    ];

    public function clients()
    {
        return $this->hasMany('App\Client', 'status_id');
    }
}
