<?php namespace App;

use DB;

class PreordersConfig
{

    private static $instance = NULL;

    private function __construct()
    {

        $row = DB::connection('tenant')->select("SELECT * FROM preorders_config");

        $properties = get_object_vars($row[0]);
        foreach ($properties as $name => $value) {
            $this->{$name} = $value;
        }
    }

    public static function getInstance()
    {

        if (!self::$instance) {
            self::$instance = new PreordersConfig();
        }

        return self::$instance;
    }

    public static function updateSettings($arr)
    {

        return DB::connection('tenant')->table('preorders_config')
            ->update($arr);
    }
}