<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomMenu extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'displayed',
        'order_num',
    ];

    public function scopeDisplayed($query)
    {
        return $query->where('displayed', '=', true);
    }

    public function menu_items()
    {
        return $this->belongsToMany('App\MenuItem', 'custom_menu_menu_item', 'custom_menu_id', 'item_id');
    }

    public function menu_groups()
    {
        return $this->belongsToMany('App\MenuGroup', 'custom_menu_menu_group', 'custom_menu_id', 'menu_group_id');
    }

    //Just for convenience
    public function items()
    {
        return $this->menu_items();
    }

    public function reservation_configurations()
    {
        return $this->hasMany('App\ReservationConfiguration');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_num');
    }
}
