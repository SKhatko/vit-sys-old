<?php namespace App;

use App\MenuTheme;
use App\MenuTitleTranslation;
use DB;

class MenuSingleton
{

    private static $instance = NULL;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        } else {
            self::$instance = new MenuSingleton();
            return self::$instance;
        }
    }

    private function __construct()
    {

        $row = DB::connection('tenant')->select("SELECT * FROM menu_config");

        $properties = get_object_vars($row[0]);
        foreach ($properties as $name => $value) {
            $this->createProperty($name, $value);
        }
    }

    private function createProperty($name, $value)
    {
        $this->{$name} = $value;
    }

    public function storeTempParams($params)
    {

        if (isset($params['_token'])) {
            unset($params['_token']);
            session()->forget('menu._token');
        }

        foreach ($params as $key => $value) {
            session()->put('menu.' . $key, $value);
        }

        return true;
    }

    public function saveChanges()
    {

        $params = session()->get('menu');
        if (is_null($params) || !is_array($params) || !count($params)) {

        } else {
            unset($params['language']);
            DB::connection('tenant')->table('menu_config')
                ->update($params);
        }

    }

    public function getTempParams()
    {

        $thisProperties = get_object_vars($this);
        $sessionProperties = session()->get('menu');

        if ($sessionProperties && is_array($sessionProperties) && count($sessionProperties)) {

        } else {
            $sessionProperties = [];
        }

        $result = [];
        foreach ($thisProperties as $key => $value) {
            $result[$key] = isset($sessionProperties[$key]) ? $sessionProperties[$key] : $thisProperties[$key];
        }

        return $result;
    }

    public function setMenusBackgroundImage($background)
    {
        DB::connection('tenant')->table('menu_config')
            ->update([
                'menus_background' => $background
            ]);
    }

    public function getParams()
    {

        if (!$this->theme_id) {
            return null;
        }

        $thisProperties = get_object_vars($this);

        $result = [];
        foreach ($thisProperties as $key => $value) {
            $result[$key] = $thisProperties[$key];
        }

        return $result;
    }

    public function resetSettings()
    {
        session()->forget('menu');
    }

    public function countVisit()
    {

        $date = date("Y-m-d");
        $uniqueVisitor = true;

        if (session()->has('visitor')) {
            $uniqueVisitor = false;
        }

        //check if date exists
        $res = DB::connection('tenant')->table('menu_visit_stats')
            ->where('date', '=', $date)
            ->first();

        //add date if doesn't exist
        if (!$res) {
            DB::connection('tenant')->table('menu_visit_stats')
                ->insert([
                    'date' => $date,
                    'views' => 0,
                    'visitors' => 0,
                ]);
        }

        $query = DB::connection('tenant')->table('menu_visit_stats')->where('date', '=', $date);

        if ($uniqueVisitor) {
            $query = $query->update([
                'views' => DB::raw('views + 1'),
                'visitors' => DB::raw('visitors + 1'),
            ]);
        } else {
            $query = $query->update([
                'views' => DB::raw('views + 1')
            ]);
        }

        session()->put('visitor', true);
    }

    public function menu_title($lang = NULL)
    {

        if (!$lang) {
            $lang = \App\Config::$language;
        }

        $titleTranslation = MenuTitleTranslation::where('language', '=', $lang)->first();

        return ($titleTranslation) ? $titleTranslation->title : '';
    }
}