<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TablePlanRecord extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'date',
        'shift',
        'table_plan_id',
    ];

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

    public function scopeTablePlan($query, $tablePlanId)
    {
        return $query->where('table_plan_id', $tablePlanId);
    }
}
