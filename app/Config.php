<?php namespace App;

use Cache;
use DB;

class Config
{

    public static $sub_domain;

    //basic config
    public static $restaurant_name;
    public static $day_start;
    public static $day_end;
    public static $night_end;
    public static $timezone;
    public static $currency;
    public static $language;
    public static $website;

    //restaurant info (contact & address)
    public static $address;
    public static $city;
    public static $zip_code;
    public static $phone;
    public static $mobile;
    public static $phone_2;
    public static $email;
    public static $country;
    public static $decimal_point;

    //reservation status config
    public static $orange_num;
    public static $red_num;

    //online settings
    public static $max_hours_before_reservation_allowed;
    public static $max_reservations_per_hour;
    public static $max_persons_per_hour;
    public static $max_online_persons;
    public static $welcome_message;


    //enabled interfaces
    //these settings are loaded/updated in middlewares
    public static $has_reception = false;
    public static $has_kitchen = false;
    public static $has_restaurant = false;
    public static $has_clients = false;
    public static $has_analytics = false;
    public static $has_admin = false;


    public static $pin = null;

    //menu translations
    //public static $menu_translation_languages;

    public static $online_reservation_url;
    public static $online_menu_url;

    public static $availableLanguages = [
        'de' => 'Deutsch',
        'en' => 'English',
    ];

    public static $availableTimezones = [
        'Europe/Berlin' => 'Europe/Berlin'
    ];

    public static $availableCountries = [
        'DE' => 'Germany',
    ];

    //helper functions
    private static function getSubDomain()
    {
        $parts = explode('.', $_SERVER["SERVER_NAME"]);
        return $parts[0];
    }

    public static $default_menu_language;

    public static function load()
    {

        self::$sub_domain = self::getSubDomain();

        $row = DB::connection('tenant')->select("SELECT * FROM config");

        self::$restaurant_name = $row[0]->restaurant_name;
        self::$day_start = $row[0]->day_start;
        self::$day_end = $row[0]->day_end;
        self::$night_end = $row[0]->night_end;
        self::$timezone = $row[0]->timezone;
        self::$currency = $row[0]->currency;
        self::$language = $row[0]->language;
        self::$website = $row[0]->website;

        self::$address = $row[0]->address;
        self::$city = $row[0]->city;
        self::$zip_code = $row[0]->zip_code;
        self::$phone = $row[0]->phone;
        self::$mobile = $row[0]->mobile;
        self::$phone_2 = $row[0]->phone_2;
        self::$email = $row[0]->email;
        self::$country = $row[0]->country;
        self::$decimal_point = $row[0]->decimal_point;

        self::$orange_num = $row[0]->orange_num;
        self::$red_num = $row[0]->red_num;

        self::$max_hours_before_reservation_allowed = $row[0]->max_hours_before_reservation_allowed;
        self::$max_reservations_per_hour = $row[0]->max_reservations_per_hour;
        self::$max_persons_per_hour = $row[0]->max_persons_per_hour;
        self::$max_online_persons = $row[0]->max_online_persons;
        self::$welcome_message = $row[0]->welcome_message;

        //self::$menu_translation_languages = $row[0]->menu_translation_languages;

        self::$online_reservation_url = 'http://' . $_SERVER['HTTP_HOST'] . '/online-reservation';
        self::$online_menu_url = 'http://' . $_SERVER['HTTP_HOST'] . '/online-menu';

        self::$default_menu_language = \App\MenuLanguage::getDefaultLanguage();

        if (property_exists($row[0], 'pin')) {
            self::$pin = $row[0]->pin;
        }
    }

    public static function update($params)
    {

        unset($params['_token']);

        DB::connection('tenant')->table('config')
            ->update($params);
    }
}