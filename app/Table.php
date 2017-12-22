<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'id',
        'persons_num',
        'shape',
        'section_id',
        'x',
        'y'
    ];

    public function section()
    {
        return $this->belongsTo('App\Section', 'section_id');
    }

    public function reservations()
    {
        return $this->hasMany('App\Reservation', 'table_id');
    }

}
