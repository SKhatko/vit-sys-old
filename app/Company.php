<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
    ];

    public function reservations()
    {
        return $this->hasMany('App\Reservation');
    }

    public function reservationsCountRelation()
    {
        return $this->hasOne('App\Reservation')->selectRaw('company_id, count(*) as count')->groupBy('company_id');
    }

    public function getReservationsCountAttribute()
    {
        return $this->reservationsCountRelation ? $this->reservationsCountRelation->count : 0;
    }
}
