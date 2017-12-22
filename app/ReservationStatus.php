<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationStatus extends Model
{

    protected $connection = 'tenant';

    /*
    protected $fillable = [
        'value',
    ];
    */

    public function scopeShowOnCreation($query)
    {
        return $query->where('show_on_creation', '=', 1);
    }

    public function reservations()
    {
        return $this->hasMany('App\Reservation', 'status_id');
    }

}
