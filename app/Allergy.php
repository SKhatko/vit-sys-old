<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{

    protected $connection = 'tenant';

    public function scopeCategory($query, $category)
    {
        return $query->where('category', '=', $category);
    }

    public function scopeFood($query)
    {
        return $query->where('category', '=', 'food');
    }

    public function scopeDrinks($query)
    {
        return $query->where('category', '=', 'drinks');
    }

    public function menu_items()
    {
        return $this->belongsToMany('App\MenuItem', 'allergy_menu_item', 'allergy_id', 'menu_item_id');
    }
}
