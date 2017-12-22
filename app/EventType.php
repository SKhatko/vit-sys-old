<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'icon'
    ];

    public function reservations()
    {
        return $this->hasMany('App\Reservation', 'event_type_id');
    }

}
