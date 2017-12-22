<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{

    protected $connection = 'tenant';

    protected $table = 'menu_groups';

    protected $fillable = [
        'name',
        'description',
        'price',
        'online_shown',
        'preorders_shown',
        'order_num',
    ];

    public function scopeDataView($query)
    {

        $view = \App\Misc::getDataView();

        if ($view == NULL) {
            return $query;
        } else {
            return $query->$view();
            //since $view will be 'online' or 'preorders'
            //These scopes are implemented below
        }
    }

    public function scopeOnline($query)
    {
        return $query->where('online_shown', '=', true);
    }

    public function scopePreorders($query)
    {
        return $query->where('preorders_shown', '=', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_num');
    }

    public function courses()
    {
        return $this->hasMany('App\Course', 'group_id')->orderBy('order_num');
    }

    public function items()
    {
        return $this->hasManyThrough('App\MenuItem', 'App\Course', 'group_id', 'id', 'id');
    }

    public function custom_menus()
    {
        return $this->belongsToMany('App\CustomMenu', 'custom_menu_menu_group', 'menu_group_id', 'custom_menu_id');
    }

    public function getItemsCount()
    {

        $itemsCount = 0;

        foreach ($this->courses as $course) {
            $itemsCount += $course->menu_items->count();
        }
        return $itemsCount;
    }

    public function translations()
    {
        return $this->hasMany('App\MenuGroupTranslation', 'group_id');
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
    public function getQuantitiesDataAttribute($rawValue) {
        if ($rawValue && strlen($rawValue)) {
            $arr = unserialize($rawValue);
            $newArr = [];
            foreach ($arr as $key => $value) {
                $newArr[intval($key)] = intval($value);
            }
            return $newArr;
        }
        return [];
    }

    public function getCategoryQuantity($categoryId) {
        if (count($this->quantities_data)) {
            $data = $this->quantities_data;
            if (isset($data[$categoryId])) {
                return $data[$categoryId];
            }
        }

        return 1;
    }
    */

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

        return null;
    }

    public function description($lang) {
        if (!$lang || $lang == \App\Config::$language) {
            return $this->description;
        }
        else {
            foreach ($this->translations as $translation) {
                if ($translation->language == $lang) {
                    if ($translation->description) {
                        return $translation->description;
                    }
                }
            }
        }

        return null;
    }
    */
}
