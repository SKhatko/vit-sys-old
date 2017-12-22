<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TablePlan extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'order_num'
    ];

    public $timestamps = false;

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_num');
    }

    public function section_map_objects()
    {
        return $this->hasMany('App\SectionMapObject');
    }
}
