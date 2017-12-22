<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'phone',
        'mobile',
        'email',
        'sticky_note',
        'status_id',
        'restaurant_newsletter',
        'vitisch_newsletter'
    ];

    public function reservations()
    {
        return $this->hasMany('App\Reservation');
    }

    public function status()
    {
        return $this->belongsTo('App\ClientStatus', 'status_id');
    }

    public function reservationsCountRelation()
    {
        return $this->hasOne('App\Reservation')->selectRaw('client_id, count(*) as count')->groupBy('client_id');
    }

    public function getReservationsCountAttribute()
    {
        return $this->reservationsCountRelation ? $this->reservationsCountRelation->count : 0;
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getActiveReservationsCountAttribute()
    {
        return $this->reservations()->active()->count();
    }

    public function getIsMaleAttribute()
    {
        return (boolean)$this->gender == 'male';
    }

    public function getIsFemaleAttribute()
    {
        return (boolean)$this->gender == 'female';
    }
}
