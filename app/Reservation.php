<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{

    use SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        //'phone',
        'company_id',
        'persons_num',
        'client_id',
        'date',
        'time',
        'description',
        'status_id',
        'table_id',
        'event_type_id',
        'offer_status_id',
        'offer_file',
        'showed',
        'showed_at',
        'left',
        'left_at',
        'is_walkin',
        'client_token',
        'created_by',
        'updated_by',
        'source',
        'ref'
    ];

    protected $appends = [
        'identifier'
    ];

    //protected $dates = ['deleted_at'];

    //override eloquent getDates so it doesn't convert updated_at and created_at into carbon objects automatically. Only deleted at
    public function getDates() {
        return [];
    }
    
    public function scopeFrom($query, $date)
    {
        return $query->where('date', '>=', $date);
    }

    public function scopeTo($query, $date)
    {
        return $query->where('date', '<=', $date);
    }
    
    public function scopeUrlToken($query, $token)
    {
        return $query->where('url_token', '=', $token);
    }

    public function scopeIdentifier($query, $identifier)
    {
        $id = self::parseIdentifier($identifier);
        return $query->where('id', '=', $id);
    }

    public function scopeActive($query)
    {
        //return $query->where('cancelled', '=', false)->where('no_show', '=', false);
        return $query->where(function ($q) {
            $q->where('status_id', '=', 1);
        });
    }

    public function getIsActiveAttribute()
    {
        return $this->status_id == 1 ? true : false;
    }

    public function scopeCancelled($query)
    {
        //return $query->where('cancelled', '=', true);
        return $query->where('status_id', '=', 2);
    }

    public function scopeNoshow($query)
    {
        //return $query->where('no_show', '=', true);
        return $query->where('status_id', '=', 3);
    }

    public function scopeWaiting($query)
    {
        return $query->where('status_id', '=', 4);
    }

    public function scopeDate($query, $date = NULL)
    {

        if (!$date) {
            $date = date("Y-m-d");
        }

        return $query->where('date', '=', $date);
    }

    public function scopeShift($query, $shift) {
        if ($shift == 'day') {
            return $query->day();
        }
        else if ($shift == 'night') {
            return $query->night();
        }
        
        return $query;
    }
    
    public function scopeAll($query, $date = NULL)
    {
        if (!$date) {
            $date = date("Y-m-d");
        }
        $date1 = date("Y-m-d", strtotime($date . ' +1 days'));

        return $query->where('date', '=', $date);
    }

    public function scopeDay($query, $date = NULL)
    {

        $date = $date ?  date("Y-m-d", strtotime($date)) : NULL;
        $end = \App\Config::$day_end;

        if ($date) {
            $query = $query->where('date', '=', $date);
        }
        $query = $query->where('time', '<', $end);
        
        return $query;
    }

    public function scopeNight($query, $date = NULL)
    {

        $date = $date ?  date("Y-m-d", strtotime($date)) : NULL;
        $start = \App\Config::$day_end;

        if ($date) {
            $query = $query->where('date', '=', $date);
        }
        $query = $query->where('time', '>=', $start);
        
        return $query;
    }


    public function scopeToday($query)
    {
        $today = date("Y-m-d");
        //$tomorrow = date("Y-m-d", strtotime("+1 day"));
        return $query->where('date', '=', $today);
    }

    public function scopeHasPreorders($query)
    {
        return $query->where('has_preorders', '=', true);
    }

    public function getIdentifierAttribute()
    {

        $idLength = strlen((string)$this->id);
        $zerosNum = 9 - $idLength;

        $leftPaddedString = "";
        for ($i = 0; $i < $zerosNum; $i++) {
            $leftPaddedString .= "0";
        }
        $leftPaddedString .= (string)$this->id;

        $last2Letters = substr($leftPaddedString, 7, 2);
        $first7Letters = substr($leftPaddedString, 0, 7);

        $convertedString = $last2Letters . $first7Letters;
        $convertedInt = (int)$convertedString;

        $base34 = base_convert($convertedInt, 10, 34);

        //replace chars to avoid similar chars/numbers like 0 and o (zero and the character O).

        //replace zeros with y
        $result = str_replace('0', 'y', (string)$base34);

        //replace ones with z
        $result = str_replace('1', 'z', $result);

        return $result;
    }

    //takes an identifier, and returns the ID
    public static function parseIdentifier($identifier)
    {

        $identifier = strtolower($identifier);

        $base34 = str_replace('y', '0', $identifier);
        $base34 = str_replace('z', '1', $base34);

        $base10 = base_convert($base34, 34, 10);

        //in case number is 1 digit
        for ($i = 0; $i < 9 - strlen($base10); $i++) {
            $base10 = '0' . $base10;
        }

        $first2Letters = substr($base10, 0, 2);
        $last7Letters = substr($base10, 2, 7);

        $str = $last7Letters . $first2Letters;
        return (int)$str;
    }

    public function status()
    {
        return $this->belongsTo('App\ReservationStatus', 'status_id');
    }

    public function offer_status()
    {
        return $this->belongsTo('App\OfferStatus', 'offer_status_id');
    }

    public function reservation_changes()
    {
        return $this->hasMany('App\ReservationChange');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function event_type()
    {
        return $this->belongsTo('App\EventType', 'event_type_id');
    }

    public function preorders()
    {
        return $this->hasMany('App\Preorder');
    }

    public function reservation_configuration()
    {
        return $this->hasOne('App\ReservationConfiguration');
    }
}
