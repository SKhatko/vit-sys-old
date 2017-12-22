<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationConfiguration extends Model
{

    protected $connection = 'tenant';

    protected $table = 'reservation_configurations';

    protected $fillable = [
        'reservation_id',
        'preorders_enabled',
        'display_images',
        'display_prices',
        'hours_limit',
        'custom_menu_id',
    ];

    public function reservation()
    {
        return $this->belongsTo('App\Reservation');
    }

    public function custom_menu()
    {
        return $this->belongsTo('App\CustomMenu');
    }
}