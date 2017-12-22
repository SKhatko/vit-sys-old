<?php namespace App\Http\Controllers;

use App\MenuGroup;
use App\MenuCategory;
use App\MenuLanguage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Config as TenantConfig;
use App\PreordersConfig;
use App\ScheduleSingleton;
use App\Misc;
use App\Offday;

use Illuminate\Http\Request;

class ConfigController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function basic()
    {
        $title = trans('admin.basic_configuration');
        $pageName = "admin-basic";

        $config = new TenantConfig();

        $currencies = [
            'EUR' => 'EUR',
            'USD' => 'USD'
        ];

        return view('admin.config.basic')->with([
            'title' => $title,
            'pageName' => $pageName,
            'config' => $config,
            'currencies' => $currencies
        ]);
    }

    public function postBasic(Request $request)
    {
        $this->validate($request, [
            'website' => 'url',
//            'day_start' => 'required',
//            'day_end' => 'required',
            'restaurant_name' => 'required',
            'currency' => 'required',
            //'timezone'	=>	'required'

            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'country' => 'required',
            'orange_num' => 'numeric|required',
            'red_num' => 'numeric|required',
        ]);

        TenantConfig::update($request->all());

        return redirect()->action('ConfigController@basic');
    }

    public function reservationStatus()
    {

        $title = trans('admin.reservation_status_configuration');
        $pageName = "reservation-status";

        $config = new TenantConfig();

        return view('admin.config.reservation_status')->with([
            'title' => $title,
            'pageName' => $pageName,

            'config' => $config,
        ]);
    }

    public function postReservationStatus(Request $request)
    {


        return redirect()->action('ConfigController@reservationStatus');

    }

    public function currentReservationStatus()
    {

        $title = trans('reception.reservation_limit_settings');

        if (session()->has('filters.reservations.date')) {
            $filterDate = date("Y-m-d", strtotime(session()->get('filters.reservations.date')));
        } else {
            $filterDate = date("Y-m-d");
        }

        return view('reception.reservations.reservation_status')->with([
            'title' => $title,
            'date' => $filterDate,
        ]);

    }

    public function clientStatus()
    {

        $title = "Client Status Configuration";

        $config = new TenantConfig();

        return view('admin.config.client_status')->with([
            'title' => $title,
            'config' => $config
        ]);
    }

    public function reservationHours()
    {

        $title = trans('admin.reservation_hours');
        $pageName = "reservation-hours";

        $schedule = ScheduleSingleton::getInstance();
        $dailySchedule = $schedule->getDailySchedule();
        $config = new TenantConfig();
        $offdays = Offday::orderBy('date', 'desc')->paginate(20);

        $shifts = [
            'day' => trans('general.day'),
            'night' => trans('general.night')
        ];

        $dayStartMinutes = $schedule::timeToMinutes($config::$day_start);
        $dayEndMinutes = $schedule::timeToMinutes($config::$day_end);
        $nightEndMinutes = $schedule::timeToMinutes($config::$night_end);
        $interval = $schedule->interval;

        if ($dayStartMinutes % $interval) {
            $dayStartMinutes = $interval - ($dayStartMinutes % $interval) + $dayStartMinutes;
        }

        if ($dayEndMinutes % $interval) {
            $dayEndMinutes = $interval - ($dayEndMinutes % $interval) + $dayEndMinutes;
        }

        return view('admin.config.reservation_hours')->with([
            'title' => $title,
            'pageName' => $pageName,
            'dailySchedule' => $dailySchedule,
            'schedule' => $schedule,
            'config' => $config,
            'dayStartMinutes' => $dayStartMinutes,
            'dayEndMinutes' => $dayEndMinutes,
            'nightEndMinutes' => $nightEndMinutes,
            'interval' => $interval,
            'shifts' => $shifts,
            'offdays' => $offdays,
        ]);
    }

    public function postReservationHours(Request $request)
    {
        $this->validate($request, [
            'day_start' => 'required',
            'day_end' => 'required',
            'night_end' => 'required',
        ]);

        $configData = [
            'day_start' => $request->input('day_start'),
            'day_end' => $request->input('day_end'),
            'night_end' => $request->input('night_end')
        ];

        TenantConfig::update($configData);

        $data = [
            'monday' => (boolean)$request->input('monday'),
            'tuesday' => (boolean)$request->input('tuesday'),
            'wednesday' => (boolean)$request->input('wednesday'),
            'thursday' => (boolean)$request->input('thursday'),
            'friday' => (boolean)$request->input('friday'),
            'saturday' => (boolean)$request->input('saturday'),
            'sunday' => (boolean)$request->input('sunday')
        ];

        if ($request->input('monday_times') && is_array($request->input('monday_times'))) {
            $data['monday_times'] = serialize($request->input('monday_times'));
        }

        if ($request->input('tuesday_times') && is_array($request->input('tuesday_times'))) {
            $data['tuesday_times'] = serialize($request->input('tuesday_times'));
        }

        if ($request->input('wednesday_times') && is_array($request->input('wednesday_times'))) {
            $data['wednesday_times'] = serialize($request->input('wednesday_times'));
        }

        if ($request->input('thursday_times') && is_array($request->input('thursday_times'))) {
            $data['thursday_times'] = serialize($request->input('thursday_times'));
        }

        if ($request->input('friday_times') && is_array($request->input('friday_times'))) {
            $data['friday_times'] = serialize($request->input('friday_times'));
        }

        if ($request->input('saturday_times') && is_array($request->input('saturday_times'))) {
            $data['saturday_times'] = serialize($request->input('saturday_times'));
        }

        if ($request->input('sunday_times') && is_array($request->input('sunday_times'))) {
            $data['sunday_times'] = serialize($request->input('sunday_times'));
        }

        ScheduleSingleton::getInstance()->updateSchedule($data);

        session()->flash('flash_message', trans('admin.preorder_settings_success_msg'));
        session()->flash('flash_message_type', 'alert-success');


        return redirect()->action('ConfigController@reservationHours');
    }

    public function online()
    {

        $title = trans('admin.online_reservation_settings');
        $pageName = "online-settings";

        $config = new TenantConfig();

        return view('admin.config.online_settings')->with([
            'title' => $title,
            'pageName' => $pageName,
            'config' => $config
        ]);

    }

    public function postOnline(Request $request)
    {

        $this->validate($request, [
            'max_hours_before_reservation_allowed' => 'required',
            'max_online_persons' => 'required',
            'max_persons_per_hour' => 'required',
            'welcome_message' => 'max:255',
        ]);

        TenantConfig::update($request->all());

        session()->flash('flash_message', 'Settings updated successfully');
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('ConfigController@online');
    }

    public function onlineLanguages()
    {
        $title = trans('admin.online_menu_settings');
        $pageName = "online-languages";

        $config = new TenantConfig();
        $menuLanguages = MenuLanguage::all();

        $publishStatuses = [
            0 => trans('admin.unpublished'),
            1 => trans('admin.published')
        ];

        $defaultLanguage = NULL;
        $menuLanguagesList = [];

        foreach ($menuLanguages as $menuLanguage) {
            if ($menuLanguage->default) {
                $defaultLanguage = $menuLanguage->language;
            }
            $menuLanguagesList[$menuLanguage->language] = trans('languages.' . $menuLanguage->language);
        }

        $languages = Misc::getLanguages();

        return view('admin.config.online_languages')->with([
            'title' => $title,
            'pageName' => $pageName,
            'config' => $config,
            'menuLanguages' => $menuLanguages,
            'menuLanguagesList' => $menuLanguagesList,
            'defaultLanguage' => $defaultLanguage,
            'languages' => $languages,
            'publishStatuses' => $publishStatuses
        ]);
    }

    public function postOnlineLanguages(Request $request)
    {
        $menuLanguage = MenuLanguage::all();

        $defaultLanguage = $request->input('default_language');
        $publishedLanguages = $request->input('published');

        foreach ($menuLanguage as $language) {
            $language->update([
                'default' => false,
                'published' => false
            ]);

            // TODO fix why default language doesn't become publisched
            if($language->language == $defaultLanguage) {
                $language->update([
                    'default' => true,
                ]);
            } else if($publishedLanguages[$language->language] == '1') {
                $language->update([
                    'published' => true
                ]);
            }
        }

        return redirect()->action('ConfigController@onlineLanguages');
    }

    public function preorders()
    {

        $title = trans('admin.preorder_settings');
        $pageName = "preorders";

        $settings = (array)\App\PreordersConfig::getInstance();

        return view('admin.config.preorders')->with([
            'title' => $title,
            'pageName' => $pageName,
            'settings' => $settings
        ]);
    }

    public function postPreorders(Request $request)
    {

        $this->validate($request, [
            'hours_limit' => 'required|numeric|min:0',
            'display_images' => 'required',
            'display_prices' => 'required'
        ]);

        $values = [
            'hours_limit' => $request->input('hours_limit'),
            'display_images' => (bool)$request->input('display_images'),
            'display_prices' => (bool)$request->input('display_prices')
        ];

        /*
        if (!PreordersConfig::getInstance()->updateSettings($values)) {
            session()->flash('flash_message', trans('admin.preorder_settings_error_msg'));
            session()->flash('flash_message_type', 'alert-danger');
        }
        else {
            session()->flash('flash_message', trans('admin.preorder_settings_success_msg'));
            session()->flash('flash_message_type', 'alert-success');
        }
        */
        //@TODO below function returning false when trying to update with same settings
        PreordersConfig::getInstance()->updateSettings($values);

        session()->flash('flash_message', trans('admin.preorder_settings_success_msg'));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('ConfigController@preorders');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
