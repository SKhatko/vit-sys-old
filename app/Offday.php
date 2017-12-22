<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Offday extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'date',
        'shift',
        'enabled',
        'reason_for_change',
    ];

    public function scopeDate($query, $date)
    {
        return $query->where('date', '=', $date);
    }

    public function scopeShift($query, $shift)
    {
        return $query->where('shift', '=', $shift);
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', '=', true);
    }

    public function scopeDisabled($query)
    {
        return $query->where('enabled', '=', false);
    }

    public function scopeFrom($query, $date)
    {
        return $query->where('date', '>=', $date);
    }

    public function scopeTo($query, $date)
    {
        return $query->where('date', '<=', $date);
    }

    public function getTimesArrayAttribute()
    {
        if ($this->times) {
            $result = unserialize($this->times);
            if (is_null($result)) {
                return [];
            }
            return $result;
        }
        return [];
    }

    public function getTimesAttribute($value)
    {
        return unserialize($value);
    }

    public function setTimesAttribute($value)
    {
        $this->attributes['times'] = serialize($value);
    }

}
