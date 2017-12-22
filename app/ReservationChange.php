<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationChange extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'reservation_id',
        'user',
        'action'
    ];

    public function reservation()
    {
        return $this->belongsTo('App\Reservation');
    }
}
