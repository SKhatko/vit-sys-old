<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuLanguage extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'language',
        'published',
        'default',
    ];

    public function scopeLanguage($query, $lang)
    {
        return $query->where('language', '=', $lang);
    }

    public function scopePublished($query)
    {
        return $query->where('published', '=', true);
    }

    public function scopeDefaultLanguage($query)
    {
        return $query->where('default', '=', true);
    }

    public static function getDefaultLanguage()
    {
        $language = MenuLanguage::defaultLanguage()->first();
        if ($language) {
            return $language->language;
        }

        return null;
    }
}
