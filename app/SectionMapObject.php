<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionMapObject extends Model
{

    protected $connection = 'tenant';

    protected $fillable = [
        'section_id',
        'table_plan_id',
        'type',
        'x',
        'y',
        'width',
        'height',
        'persons_num',
        'object_num',
        'label',
        'border-radius',
    ];

    protected $hidden = ["created_at", "updated_at"];

    public function scopeTablePlan($query, $tablePlanId)
    {
        return $query->where('table_plan_id', '=', $tablePlanId);
    }

    public function scopeSectionId($query, $sectionId)
    {
        return $query->where('section_id', '=', $sectionId);
    }
}
