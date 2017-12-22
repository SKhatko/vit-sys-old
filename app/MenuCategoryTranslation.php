<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuCategoryTranslation extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'category_id',
        'language',
        'name',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo('App\MenuCategory', 'category_id');
    }

    public function menu_category()
    {
        return $this->belongsTo('App\MenuCategory', 'category_id');
    }

}
