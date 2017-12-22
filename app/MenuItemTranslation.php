<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItemTranslation extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'item_id',
        'language',
        'name',
        'description'
    ];

    public function item()
    {
        return $this->belongsTo('App\MenuItem', 'item_id');
    }

    public function menu_item()
    {
        return $this->belongsTo('App\MenuItem', 'item_id');
    }
}
