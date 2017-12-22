<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuTitleTranslation extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'language',
        'title',
    ];
}
