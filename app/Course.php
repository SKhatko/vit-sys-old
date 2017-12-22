<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'group_id',
        'quantity',
        'order_num'
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_num');
    }

    public function menu_group()
    {
        return $this->belongsTo('App\MenuGroup', 'group_id');
    }

    public function menu_items()
    {

        return $this->belongsToMany('App\MenuItem', 'course_menu_item', 'course_id', 'item_id');
    }

    public function items()
    {
        return $this->menu_items();
    }

    public function translatedName($lang)
    {

        foreach ($this->translations as $translation) {
            if ($translation->language == $lang) {
                if ($translation->name) {
                    return $translation->name;
                }
            }
        }

        return null;
    }

}
