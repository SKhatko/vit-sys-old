<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'description',
        'order_num'
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_num');
    }

    public function objects()
    {
        return $this->hasMany('App\SectionMapObject');
    }
}
