<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoppedDay extends Model
{

    use SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'date',
        'shift',
        'online_closed',
        'system_closed',
        'online_stop_num',
        'system_stop_num',
        'user',
    ];

    protected $dates = ['deleted_at'];

    public function scopeDate($query, $date)
    {
        $date = date("Y-m-d", strtotime($date));
        return $query->where('date', '=', $date);
    }

    public function scopeShift($query, $shift)
    {
        return $query->where('shift', '=', $shift);
    }

    public function scopeFrom($query, $date)
    {
        $date = date("Y-m-d", strtotime($date));
        return $query->where('date', '>=', $date);
    }

    public function scopeTo($query, $date)
    {
        return $query->where('date', '<=', $date);
    }

    public function scopeDateShift($query, $date, $shift)
    {
        $date = date("Y-m-d", strtotime($date));
        return $query->where('date', '=', $date)->where('shift', '=', $shift);
    }

}
