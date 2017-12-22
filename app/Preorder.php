<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'reservation_id',
        'name',
        'notes'
    ];


    public function scopeName($query, $name)
    {
        return $query->where('name', 'LIKE', $name);
    }

    public function reservation()
    {
        return $this->belongsTo('App\Reservation');
    }

    public function menu_items()
    {
        return $this->belongsToMany('App\MenuItem', 'item_preorder', 'preorder_id', 'item_id')
            ->withPivot('quantity');
    }

    //for convenience
    public function items()
    {
        return $this->menu_items();
    }

    public function groups()
    {
        return $this->belongsToMany('App\MenuGroup', 'group_preorder', 'preorder_id', 'group_id')
            ->withPivot(['items', 'quantity']);
    }
}
