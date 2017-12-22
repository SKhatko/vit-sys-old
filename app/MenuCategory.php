<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'image'
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_num');
    }

    public function menu_items()
    {
        if (\App\Misc::getDataView() == 'online') {
            return $this->hasMany('App\MenuItem', 'category_id')->where('online_shown', '=', true)->orderBy('order_num');
        } else if (\App\Misc::getDataView() == 'preorders') {
            return $this->hasMany('App\MenuItem', 'category_id')->where('preorders_shown', '=', true)->orderBy('order_num');
        } else {
            return $this->hasMany('App\MenuItem', 'category_id')->orderBy('order_num');
        }
    }

    public function translations()
    {
        return $this->hasMany('App\MenuCategoryTranslation', 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\MenuCategory', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\MenuCategory', 'parent_id');
    }

    public function itemsCount()
    {
        return $this->menu_items()
            ->selectRaw('category_id, count(*) as aggregate')
            ->groupBy('category_id');
    }

    public function getItemsCountAttribute()
    {
        // if relation is not loaded already, let's do it first
        if (!array_key_exists('itemsCount', $this->relations)) {
            $this->load('itemsCount');
        }

        $related = $this->getRelation('itemsCount')->first();

        // then return the count directly
        return ($related) ? (int)$related->aggregate : 0;
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

    public function translatedDescription($lang)
    {

        foreach ($this->translations as $translation) {
            if ($translation->language == $lang) {
                if ($translation->description) {
                    return $translation->description;
                }
            }
        }

        return null;

    }

    /*
    public function name($lang) {
        if (!$lang || $lang == \App\Config::$language) {
            return $this->name;
        }
        else {
            foreach ($this->translations as $translation) {
                if ($translation->language == $lang) {
                    if ($translation->name) {
                        return $translation->name;
                    }
                }
            }
        }

        return $this->name;
    }
    */

}
