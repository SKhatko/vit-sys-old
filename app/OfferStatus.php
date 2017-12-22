<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferStatus extends Model
{

    protected $connection = 'tenant';

    public function reservations()
    {
        return $this->hasMany('App\Reservation', 'offer_status_id');
    }

}
