<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'category_id',
        'image',
        'description',
        'price',
        'online_shown',
        'preorders_shown'
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_num');
    }

    public function courses()
    {
        return $this->belongsToMany('App\Course', 'course_menu_item', 'item_id', 'course_id');
    }

    public function scopeOnline($query)
    {
        return $query->where('online_shown', '=', true);
    }

    public function scopePreorders($query)
    {
        return $query->where('preorders_shown', '=', true);
    }

    public function category()
    {
        return $this->belongsTo('App\MenuCategory', 'category_id');
    }

    public function allergies()
    {
        return $this->belongsToMany('App\Allergy', 'allergy_menu_item', 'menu_item_id', 'allergy_id');
    }

    public function preorders()
    {
        return $this->belongsToMany('App\Preorder', 'item_preorder', 'item_id', 'preorder_id');
    }

    public function getAllergyIdsAttribute()
    {

        $result = [];
        foreach ($this->allergies as $allergy) {
            $result[] = $allergy->id;
        }

        return $result;
    }

    public function translations()
    {
        return $this->hasMany('App\MenuItemTranslation', 'item_id');
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

}
