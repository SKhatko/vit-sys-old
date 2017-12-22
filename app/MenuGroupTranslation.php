<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuGroupTranslation extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'group_id',
        'language',
        'name',
        'description'
    ];

    public function group()
    {
        return $this->belongsTo('App\MenuGroup', 'group_id');
    }

    public function menu_group()
    {
        return $this->belongsTo('App\MenuGroup', 'group_id');
    }
}
