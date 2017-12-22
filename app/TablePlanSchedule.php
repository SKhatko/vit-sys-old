<?php

namespace App;

use DB;

class TablePlanSchedule
{

    private static $instance = NULL;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        } else {
            self::$instance = new TablePlanSchedule();
            return self::$instance;
        }
    }

    private function __construct()
    {
        $row = DB::connection('tenant')->select("SELECT * FROM table_plan_schedule");

        $properties = get_object_vars($row[0]);
        foreach ($properties as $name => $value) {
            $this->createProperty($name, $value);
        }
    }

    private function createProperty($name, $value)
    {
        $this->{$name} = $value;
    }

    public function setDailySchedule($id)
    {

        $data = [];

        foreach ($this as $key => $value) {
            $data[$key] = $id;
        }

        if (count($data)) {
            $this->updateSchedule($data);
        }
    }

    public function updateSchedule($data)
    {
        DB::connection('tenant')->table('table_plan_schedule')->update($data);
    }

    public function getDailySchedule()
    {
        return [
            'monday' => [
                'day' => $this->monday_day,
                'night' => $this->monday_night
            ],
            'tuesday' => [
                'day' => $this->tuesday_day,
                'night' => $this->tuesday_night
            ],
            'wednesday' => [
                'day' => $this->wednesday_day,
                'night' => $this->wednesday_night
            ],
            'thursday' => [
                'day' => $this->thursday_day,
                'night' => $this->thursday_night
            ],
            'friday' => [
                'day' => $this->friday_day,
                'night' => $this->friday_night
            ],
            'saturday' => [
                'day' => $this->saturday_day,
                'night' => $this->saturday_night
            ],
            'sunday' => [
                'day' => $this->sunday_day,
                'night' => $this->sunday_night
            ]
        ];
    }

    public function getTablePlanIdOnDateAndShift($date, $shift)
    {
        $tablePlanRecord = TablePlanRecord::date($date)->shift($shift)->first();
        $dayOfWeek = strtolower(date('l', strtotime($date)));
        $dayAndShift = $dayOfWeek . '_' . $shift;

        if ($tablePlanRecord) {
            return $tablePlanRecord->table_plan_id;
        } else if ($this->$dayAndShift) {
            return $this->$dayAndShift;
        } else {
            return $this->default_table_plan_id;
        }
    }

    public function tablePlanDeleted($id)
    {

        $data = [];
        foreach ($this as $key => $value) {
            if ($value == $id) {
                $data[$key] = null;
            }
        }

        if (count($data)) {
            $this->updateSchedule($data);
        }
    }
}
