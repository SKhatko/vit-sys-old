<?php namespace App\Http\Controllers;

use App\Misc;
use App\ScheduleSingleton;
use App\Section;
use App\StoppedDay;
use App\Offday;
use App\Client;
use App\Company;
use App\Reservation;
use App\ReservationStatus;
use App\ReservationChange;
use App\ReservationConfiguration;
use App\CustomMenu;
use App\TablePlanSchedule;
use App\User;
use App\ClientStatus;
use App\EventType;
use App\OfferStatus;
use App\Http\Requests;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Cache;

use App\Config as TenantConfig;

use App\Http\Controllers\Controller;

use Doctrine\DBAL\Platforms\Keywords\ReservedKeywordsValidator;
use Illuminate\Http\Request;
use DB;
use App\TablePlan;
use App\SectionMapObject;

class ReservationsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('reception.reservations');
        $pageName = 'reception';

        //Schedule functions
        $schedule = ScheduleSingleton::getInstance();
        $minDate = date("Y-m-d", strtotime("-" . $schedule->daysAhead . " days"));
        $maxDate = date("Y-m-d", strtotime("+" . $schedule->daysAhead . " days"));

        $badNightDates = $schedule->getDisabledDates($minDate, $maxDate, 'night', true);
        $badDayDates = $schedule->getDisabledDates($minDate, $maxDate, 'day', true);

        $date = NULL;
        if (session()->has('flash_filter_date')) {
            $date = date("Y-m-d", strtotime(session()->get('flash_filter_date')));
        } else {
            $date = date("Y-m-d");
        }

        $filterDate = date("Y-m-d", strtotime($date));

//        $filterDate = '2017-11-1';

        $dayStart = TenantConfig::$day_start;
        $dayEnd = TenantConfig::$day_end;
        $nightEnd = TenantConfig::$night_end;

        if (session()->has('flash_filter_shift')) {
            $filterTimeOfDay = session()->get('flash_filter_shift');
        } else {
            if (session()->has('flash_filter_time')) {
                $time = date("H:i:s", strtotime(session()->get('flash_filter_time')));
            } else {
                $time = date("H:i:s");
            }

            if ($time > $dayEnd) {
                $filterTimeOfDay = 'night';
            } else {
                $filterTimeOfDay = 'day';
            }
        }

//        $reservations = Reservation::with('client')->active()->date($filterDate)->orderBy('time')->get();
//        $canceled = Reservation::with('client')->cancelled()->date($filterDate)->orderBy('time')->get();
//        $noshow = Reservation::with('client')->noshow()->date($filterDate)->orderBy('time')->get();

//        $reservations = Reservation::with('client')->date($filterDate)->orderBy('time')->get();

        $clientStatusesRaw = Cache::remember('client_statuses', 10000, function () {
            return ClientStatus::all();
        });
        $clientStatuses = [];
        foreach ($clientStatusesRaw as $id => $status) {
            $clientStatusesRaw[$id] = trans('crm.' . $status);
        }

        $eventTypesRaw = Cache::remember('event_types', 10000, function () {
            return EventType::all();
        });
        $eventTypes = [];
        foreach ($eventTypesRaw as $id => $name) {
            $eventTypes[$id] = trans('reception.' . $name);
        }

        $offerStatusesRaw = Cache::remember('offer_statuses', 10000, function () {
            return OfferStatus::all();
        });
        $offerStatuses = [];
        foreach ($offerStatusesRaw as $id => $name) {
            $offerStatuses[$id] = trans('reception.' . $name);
        }

        $sections = Section::ordered()->pluck('name', 'id')->all();

        return view('reception.reservations.index')->with([
            'title' => $title,
            'pageName' => $pageName,
            'dayStart' => $dayStart,
            'nightEnd' => $nightEnd,
            'filterDate' => $filterDate,
            'filterTimeOfDay' => $filterTimeOfDay,
            'sections' => $sections,

            'clientStatuses' => $clientStatuses,
            'eventTypes' => $eventTypes,
            'offerStatuses' => $offerStatuses,

            'badNightDates' => $badNightDates,
            'badDayDates' => $badDayDates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create($date = NULL)
    {
        $title = trans('reception.new_reservation');
        $pageName = 'reception-create';

        $time = date("H:i");

        if ($time > TenantConfig::$day_start && $time < TenantConfig::$night_end) {

            $hours = date('H');
            $minutes = date('i');

            if ($minutes < 15) {
                $minutes = 15;
            } else if ($minutes < 30) {
                $minutes = 30;
            } else if ($minutes < 45) {
                $minutes = 45;
            } else if ($minutes > 45) {
                $hours++;
                $minutes = 0;
            }

            $time = date('H:i', strtotime($hours . ':' . $minutes));
            $time = $time > TenantConfig::$night_end ? TenantConfig::$day_start : $time;
        } else {
            $time = TenantConfig::$day_start;
        }

        $statusesRaw = Cache::remember('client_statuses', 10000, function () {
            return ReservationStatus::showOnCreation()->get();
        });

        $statuses = [];
        foreach ($statusesRaw as $status) {
            $statuses[$status->id] = trans('reception.' . $status->name);
        }

        $clientStatusesRaw = Cache::remember('client_statuses', 10000, function () {
            return ClientStatus::all();
        });

        $clientStatuses = [];
        foreach ($clientStatusesRaw as $clientStatus) {
            $clientStatuses[$clientStatus->id] = trans('crm.' . $clientStatus->name);
        }

        $eventTypesRaw = Cache::remember('event_types', 10000, function () {
            return EventType::all();
        });

        $eventTypes = [];
        foreach ($eventTypesRaw as $eventType) {
            $eventTypes[$eventType->id] = trans('reception.' . $eventType->name);
        }

        $offerStatusesRaw = Cache::remember('offer_statuses', 10000, function () {
            return OfferStatus::all();
        });
        $offerStatuses = [];

        foreach ($offerStatusesRaw as $offerStatus) {
            $offerStatuses[$offerStatus->id] = trans('reception.' . $offerStatus->name);
        }

        $sections = Section::ordered()->pluck('name', 'id')->all();

        $preorderConfigurations = [
            0 => trans('reception.default_configurations'),
            1 => trans('reception.custom_configurations')
        ];

        $customMenus = ["" => trans('reception.default_preorders_menu')] + CustomMenu::displayed()->pluck('name', 'id')->all();

        if ($date) {
            $filterDate = date("Y-m-d", strtotime($date));
        } else {
            $filterDate = date("Y-m-d");
        }

        return view('reception.reservations.create')->with([
            'title' => $title,
            'pageName' => $pageName,
            'date' => $date,
            'time' => $time,
            'statuses' => $statuses,
            'clientStatuses' => $clientStatuses,
            'eventTypes' => $eventTypes,
            'offerStatuses' => $offerStatuses,
            'preorderConfigurations' => $preorderConfigurations,

            'customMenus' => $customMenus,
            'filterDate' => $filterDate,
            'sections' => $sections,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ReservationRequest $request)
    {
        $date = date("Y-m-d", strtotime($request->input('date')));
        $time = date("H:i:s", strtotime($request->input('time') . ':00'));

        $request->merge(['date' => $date, 'time' => $time]);

        if ($request->input('last_name')) {

            $clientData = $request->all();
            if (isset($clientData['client_status_id'])) {
                $clientData['status_id'] = $clientData['client_status_id'];
            }

            if ($request->input('client_id')) {
                $client = Client::findOrFail($request->input('client_id'));
                $client->update($clientData);
            } else {
                if (!$request->input('phone') && !$request->input('mobile')) {
                    //check e-mail
                    $exists = NULL;
                    if ($request->input('email')) {
                        $exists = Client::where('email', 'LIKE', $request->input('email'))->first();
                    }

                    if (!$exists) {
                        //give it another try with first name and last name
                        $exists = Client::where('first_name', 'LIKE', $request->input('first_name'))
                            ->where('last_name', 'LIKE', $request->input('last_name'))
                            ->first();
                    }

                    if ($exists) {
                        $exists->update($clientData);
                        $request->merge(['client_id' => $exists->id]);
                    } else {
                        $client = Client::create($clientData);
                        $request->merge(['client_id' => $client->id]);
                    }
                } else {
                    $client = Client::create($clientData);
                    $request->merge(['client_id' => $client->id]);
                }
            }
        }

        if (!$request->input('table_id')) {
            $request->merge(['table_id' => NULL]);
        }

        if (!$request->input('company_name') && !$request->input('company_id')) {
            $request->merge(['company_id' => NULL]);
        } else if ($request->input('company_name') && !$request->input('company_id')) {
            $company = Company::create(['name' => $request->input('company_name')]);
            $request->merge(['company_id' => $company->id]);
        }

        if ($request->file('offer_file_upload')) {
            $subDomain = TenantConfig::$sub_domain;
            $fileName = date("Y_m_d-H_i_s-") . uniqid() . '-offer.' . $request->file('offer_file_upload')->getClientOriginalExtension();
            $request->file('offer_file_upload')->move(base_path() . '/storage/app/offers/' . $subDomain . '/', $fileName);
            $request->merge(['offer_file' => $fileName]);
        }

        $request->merge([
            'created_by' => $request->input('user'),
            'updated_by' => $request->input('user'),
        ]);

        $reservation = Reservation::create($request->all());

        //set url_token
        $urlToken = hash('sha512', $reservation->identifier . '%*' . $reservation->id);

        $reservation->url_token = $urlToken;
        $reservation->save();

        //handle reservation preorders configuration
        $this->handleReservationConfiguration($request, $reservation);

        $logMessage = 'Reservation created';
        $this->logChange($request, $reservation->id, $logMessage);

        session()->flash('flash_message', trans('reception.store_reservation_success_msg', ['reservation_id' => $reservation->identifier]));
        session()->flash('flash_message_type', 'alert-success');
        session()->flash('flash_filter_date', $date);
        session()->flash('flash_filter_time', $time);

        return redirect()->action('ReservationsController@index');
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
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $reservation = Reservation::with('reservation_configuration')->findOrFail($id);
        $title = trans('reception.edit_reservation');
        $pageName = 'edit-reservation';

        $users = User::pluck('name', 'id')->all();
        $eventTypes = EventType::all();
        $offerStatuses = OfferStatus::all();
        $customMenus = ["" => trans('reception.default_preorders_menu')] + CustomMenu::displayed()->pluck('name', 'id')->all();

        $statusesRaw = Cache::remember('client_statuses', 10000, function () {
            return ReservationStatus::showOnCreation()->get();
            //todo trans
        });

        $statuses = [];
        foreach ($statusesRaw as $status) {
            $statuses[$status->id] = trans('reception.' . $status->name);
        }

        $clientStatusesRaw = Cache::remember('client_statuses', 10000, function () {
            return ClientStatus::all();
        });

        $clientStatuses = [];
        foreach ($clientStatusesRaw as $clientStatus) {
            $clientStatuses[$clientStatus->id] = trans('crm.' . $clientStatus->name);
        }

        $eventTypesRaw = Cache::remember('event_types', 10000, function () {
            return EventType::all();
        });

        $eventTypes = [];
        foreach ($eventTypesRaw as $eventType) {
            $eventTypes[$eventType->id] = trans('reception.' . $eventType->name);
        }

        $offerStatusesRaw = Cache::remember('offer_statuses', 10000, function () {
            return OfferStatus::all();
        });
        $offerStatuses = [];

        foreach ($offerStatusesRaw as $offerStatus) {
            $offerStatuses[$offerStatus->id] = trans('reception.' . $offerStatus->name);
        }

        $preordersCount = $reservation->preorders()->count();

        $sections = Section::ordered()->pluck('name', 'id')->all();

        $preorderConfigurations = [
            0 => trans('reception.default_configurations'),
            1 => trans('reception.custom_configurations')
        ];

        return view('reception.reservations.edit')->with([
            'reservation' => $reservation,
            'title' => $title,
            'pageName' => $pageName,
            'statuses' => $statuses,
            'clientStatuses' => $clientStatuses,
            'eventTypes' => $eventTypes,
            'offerStatuses' => $offerStatuses,
            'customMenus' => $customMenus,
            'filterDate' => NULL,
            'users' => $users,
            'preordersCount' => $preordersCount,
            'customMenus' => $customMenus,
            'sections' => $sections,
            'preorderConfigurations' => $preorderConfigurations
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $date = date("Y-m-d", strtotime($request->input('date')));
        $time = date("H:i:s", strtotime($request->input('time_h') . ':' . $request->input('time_m') . ':00'));

        $request->merge(['date' => $date, 'time' => $time]);

        if ($request->input('last_name')) {

            $clientData = $request->all();
            if (isset($clientData['client_status_id'])) {
                $clientData['status_id'] = $clientData['client_status_id'];
            }

            if ($request->input('client_id')) {
                $client = Client::findOrFail($request->input('client_id'));
                $client->update($clientData);
            } else {
                if (!$request->input('phone') && !$request->input('mobile')) {

                    //check e-mail
                    $exists = NULL;
                    if ($request->input('email')) {
                        $exists = Client::where('email', 'LIKE', $request->input('email'))->first();
                    }

                    if (!$exists) {
                        //give it another try with first name and last name
                        $exists = Client::where('first_name', 'LIKE', $request->input('first_name'))
                            ->where('last_name', 'LIKE', $request->input('last_name'))
                            ->first();
                    }

                    if ($exists) {
                        $exists->update($clientData);
                        $request->merge(['client_id' => $exists->id]);
                    } else {
                        $client = Client::create($clientData);
                        $request->merge(['client_id' => $client->id]);
                    }
                } else {
                    $client = Client::create($clientData);
                    $request->merge(['client_id' => $client->id]);
                }
            }
        }

        if (!$request->input('company_name') && !$request->input('company_id')) {
            $request->merge(['company_id' => NULL]);
        } else if ($request->input('company_name') && !$request->input('company_id')) {
            $company = Company::create(['name' => $request->input('company_name')]);
            $request->merge(['company_id' => $company->id]);
        }

        if ($request->file('offer_file_upload')) {

            $subDomain = TenantConfig::$sub_domain;
            $fileName = date("Y_m_d-H_i_s-") . uniqid() . '-offer.' . $request->file('offer_file_upload')->getClientOriginalExtension();
            $request->file('offer_file_upload')->move(base_path() . '/storage/app/offers/' . $subDomain . '/', $fileName);
            $request->merge(['offer_file' => $fileName]);
        } else if ($request->input('delete_offer')) {
            $request->merge(['offer_file' => NULL]);
        }

        $request->merge([
            'updated_by' => $request->input('user')
        ]);


        $reservation->update($request->all());

        $logMessage = 'Reservation edited';
        $this->logChange($request, $reservation->id, $logMessage);

        //handle reservation preorders configuration
        $this->handleReservationConfiguration($request, $reservation);

        session()->flash('flash_message', trans('reception.update_reservation_success_msg', ['reservation_id' => $reservation->identifier]));
        session()->flash('flash_message_type', 'alert-success');
        session()->flash('flash_filter_date', $date);
        session()->flash('flash_filter_time', $time);

        return redirect()->action('ReservationsController@index');
    }

    private function handleReservationConfiguration(Request $request, Reservation $reservation)
    {

        if ($request->input('configuration_type') == 'custom') {

            $customMenuId = NULL;
            if ($request->input('custom_menu_id')) {
                $customMenu = CustomMenu::findOrFail((int)$request->input('custom_menu_id'));
                $customMenuId = $customMenu->id;
            }

            $reservation->reservation_configuration()->delete();

            $newConfig = ReservationConfiguration::create([
                'reservation_id' => $reservation->id,
                'display_images' => (boolean)$request->input('display_images'),
                'display_prices' => (boolean)$request->input('display_prices'),
                'hours_limit' => (int)$request->input('hours_limit'),
                'custom_menu_id' => $customMenuId
            ]);
        } else if ($request->input('configuration_type') == 'default') {
            $reservation->reservation_configuration()->delete();
        }
    }

    public function getAvailableTimesForDate($date, $shift)
    {
        return ScheduleSingleton::getInstance()->getAvailableTimesForDate($date, $shift, NULL, false);
    }

    public function serveOffer($id)
    {
        $reservation = Reservation::findOrFail($id);

        $fileName = $reservation->offer_file;
        $subDomain = TenantConfig::$sub_domain;
        $filePath = storage_path('app/offers/' . $subDomain . '/' . $fileName);

        if (file_exists($filePath)) {
            $file_info = new \finfo(FILEINFO_MIME); // object oriented approach!
            $mime_type = $file_info->buffer(file_get_contents($filePath));  // e.g. gives "image/jpeg"

            header("Content-type:" . $mime_type);
            header("Content-Disposition:inline;filename='" . $fileName . "'");

            echo file_get_contents($filePath);
        } else {
            echo 'Error occurred. File does not exist';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        $logMessage = 'Reservation deleted';
        return $this->logChange($request, $id, $logMessage);
    }

    public function updateLights(Request $request)
    {

        $this->validate($request, [
            'user' => 'required'
        ]);

        $date = date("Y-m-d", strtotime($request->input('date')));
        $shift = $request->input('shift');
        $user = $request->input('user');

        if (!$shift || $shift == 'all') {
            return;
        } else {
            StoppedDay::where('date', '=', $date)->where('shift', '=', $shift)->delete();

            StoppedDay::create([
                'date' => $date,
                'shift' => $shift,
                'online_closed' => $request->input('online_closed'),
                'online_stop_num' => $request->input('online_stop_num'),
                'system_closed' => false,
                'system_stop_num' => null,
                'user' => $user,
            ]);
        }

        return redirect()->action('ReservationsController@index');
    }

    public function logChange($request, $id, $action)
    {
        $user = '';
        if ($request->input('user')) {
            $user = $request->input('user');
        }

        ReservationChange::create([
            'reservation_id' => $id,
            'user' => $user,
            'action' => $action
        ]);

        return '1';
    }

    //Ajax Requests
    public function setStatus(Request $request, $id)
    {
        $statusValue = $request->input('value');
        if (!$statusValue) {
            return '0';
        }

        $status = ReservationStatus::where('name', '=', $statusValue)->first();
        if (!$status) {
            return '0';
        }

        $logMessage = 'status update (' . $statusValue . ')';
        if ($request->input('user')) {
            $logMessage .= ' - <strong>' . $request->input('user') . '</strong>';
        }

        $reservation = Reservation::findOrFail($id);
        $reservation->status_id = $status->id;
        $reservation->client_token = NULL;
        $reservation->updated_by = $request->input('user');

        /*
        if ($statusValue == 'cancelled') {
            $request->cancelled_at = date("Y-m-d H:i:s");
        }
        */

        if ($reservation->save()) {
            return $this->logChange($request, $id, $logMessage);
        }
        return '0';
    }

    public function setActiveStatus(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);
        $activeStatus = ReservationStatus::where('name', '=', 'active')->first();
        $reservation->status_id = $activeStatus->id;

        if ($reservation->save()) {
            return $this->logChange($request, $id, 'active');
        }

        return '0';

    }

    public function markShowed(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);
        $data = array(
            'showed' => true,
            'showed_at' => date("Y-m-d H:i:s"),
            'client_token' => csrf_token()
        );

        if ($reservation->update($data)) {
            return $this->logChange($request, $id, 'mark_showed');
        }

        return '0';
    }

    public function unmarkShowed(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);
        $data = array(
            'showed' => false,
            'showed_at' => NULL,
            'client_token' => csrf_token()
        );


        if ($reservation->update($data)) {
            return $this->logChange($request, $id, 'unmark_showed');
        }

        return '0';
    }

    public function markLeft(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);
        $data = array(
            'left' => true,
            'left_at' => date("Y-m-d H:i:s"),
            'client_token' => csrf_token()
        );

        if ($reservation->update($data)) {
            return $this->logChange($request, $id, 'mark_left');
        }

        return '0';
    }

    public function unmarkLeft(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);
        $data = array(
            'left' => false,
            'left_at' => NULL,
            'client_token' => csrf_token()
        );

        if ($reservation->update($data)) {
            return $this->logChange($request, $id, 'unmark_left');
        }

        return '0';
    }

    public function cancel(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);
        $cancelledStatus = ReservationStatus::where('name', '=', 'cancelled')->first();
        $reservation->status_id = $cancelledStatus->id;

        if ($reservation->save()) {
            return $this->logChange($request, $id, 'cancel');
        }

        return '0';
    }

    public function uncancel(Request $request, $id)
    {

        return $this->setActiveStatus($request, $id);
    }

    public function markNoShow(Request $request, $id)
    {

        $reservation = Reservation::findOrFail($id);

        $data = array(
            'no_show' => true,
            'no_show_at' => date("Y-m-d H:i:s"),
            'no_show_by' => $request->input('user'),
            'client_token' => NULL
        );

        if ($reservation->update($data)) {
            return $this->logChange($request, $id, 'mark_no_show');
        }

        return '0';

    }

    public function unmarkNoShow(Request $request, $id)
    {

        return $this->setActiveStatus($request, $id);

    }

    public function updateTable(Request $request, $id)
    {
        $table = $request->input('table');
        $reservation = Reservation::findOrFail($id);
        $data = array(
            'table_id' => $table,
            'client_token' => csrf_token()
        );

        return (string)$reservation->update($data);
    }

    public function postWalkin(Request $request)
    {
        $this->validate($request, [
            'persons_num' => 'required|numeric',
            'user' => 'required',
        ]);

        $date = date("Y-m-d");
        $time = date("H:i:s");

        $built = [
            'client_id' => NULL,
            'company_id' => NULL,
            'date' => $date,
            'time' => $time,
            'showed' => true,
            'showed_at' => $date,
            'is_walkin' => true,
            'created_by' => $request->input('user'),
            'updated_by' => $request->input('user'),
        ];

        $name = $request->input('name');
        if (!$name || empty($name)) {
            $built['name'] = "Walk In";
        } else {
            $built['name'] = $name;
        }

        $request->merge($built);

        if (!$request->input('table_id')) {
            $request->merge(['table_id' => null]);
        }

        $reservation = Reservation::create($request->all());

        session()->flash('flash_message', trans('reception.store_reservation_success_msg', ['reservation_id' => $reservation->identifier]));
        session()->flash('flash_message_type', 'alert-success');
        session()->flash('flash_filter_date', $date);

        return redirect()->action('ReservationsController@index');
    }

    private function getHourlyData($date, $timeOfDay, $reservations = NULL)
    {

        $date = date("Y-m-d", strtotime($date));

        if (!$reservations) {
            if ($timeOfDay && $timeOfDay != 'all') {
                //session()->put('filters.reservations.time_of_day', $timeOfDay);
                $reservations = Reservation::with(['client', 'status','event_type', 'company'])->{$timeOfDay}($date)->get();
            } else {
                //session()->forget('filters.reservations.time_of_day');
                $reservations = Reservation::with(['client', 'status','event_type', 'company'])->date($date)->get();
            }
        }

        /** Hourly data **/
        $shiftStart = NULL;
        $shiftEnd = NULL;
        if ($timeOfDay != 'all') {
            $shiftStart = ($timeOfDay == 'day') ? TenantConfig::$day_start : TenantConfig::$day_end;
            $shiftEnd = ($timeOfDay == 'day') ? date("H:i", strtotime(TenantConfig::$day_end . '-1 second')) : TenantConfig::$night_end;
        } else {
            $shiftStart = TenantConfig::$day_start;
            $shiftEnd = TenantConfig::$night_end;
        }

        $hourlyData = [];

        $shiftStart = intval(date("H", strtotime($shiftStart)));
        $shiftEnd = intval(date("H", strtotime($shiftEnd)));
        $thisTime = $shiftStart;

        while ($thisTime <= $shiftEnd) {
            $hourlyData[$thisTime] = [
                'reservations' => 0,
                'persons' => 0,
            ];
            $thisTime += 1;
        }

        //$personsNum = 0;
        foreach ($reservations as $reservation) {
            if ($reservation->is_active) {

                $hourRange = date("H", strtotime($reservation->time));
                $hourRange = (int)$hourRange;

                if (!isset($hourlyData[$hourRange])) {
                    $hourlyData[$hourRange] = [
                        'reservations' => 0,
                        'persons' => 0,
                    ];
                }

                $hourlyData[$hourRange]['reservations'] += 1;
                $hourlyData[$hourRange]['persons'] += $reservation->persons_num;
            }
        }

        ksort($hourlyData);
        return $hourlyData;
    }

    public function getRecords($date, $timeOfDay, $last = NULL)
    {
        $preordersOnly = isset($_GET['preorders_only']);
        $preorders = $preordersOnly || isset($_GET['preorders']);

        $date = date("Y-m-d", strtotime($date));

        $currDateTime = date("Y-m-d H:i:s");
        if ($last != NULL) {
            $last = date("Y-m-d H:i:s", strtotime($last));

        }

        //get relationships
        $relations = ['client', 'status', 'event_type', 'company'];
        if ($preorders) {
            $relations[] = 'preorders.items';
        }

        //get reservations
        $reservations = Reservation::with($relations);
        if ($preordersOnly) {
            $reservations = $reservations->where('has_preorders', '=', true);
        }
        if ($timeOfDay == 'all') {
            $reservations = $reservations->date($date);
        } else {
            $reservations = $reservations->{$timeOfDay}($date);
        }
        $reservations = $reservations->get();

        //get hourly data before we unset reservations
        $hourlyData = $this->getHourlyData($date, $timeOfDay, $reservations);

        //loop over reservations
        //count persons
        //set formatted preorders_summary attribute
        $personsNum = 0;
        $thisToken = csrf_token();
        foreach ($reservations as $key => $reservation) {
            if ($preorders) {
                if ($preordersOnly || count($reservation->preorders)) {
                    $reservation->preorders_summary = Misc::getPreordersSummary($reservation->preorders);
                } else {
                    $reservation->preorders_summary = NULL;
                }
            }
            if ($reservation->is_active) {
                $personsNum += $reservation->persons_num;
            }

            if ($last != NULL) {

                if (strtotime($reservation->updated_at) < strtotime($last) || ($reservation->client_token && $reservation->client_token == $thisToken)) {
                    unset($reservations[$key]);
                }
            }
        }

        $reservations = $reservations->values();

        $onlineClosed = false;
        $red = TenantConfig::$red_num;
        $yellow = TenantConfig::$orange_num;

        $color = "green";

        //get custom days data (offdays)
        //0 means no record
        //1 means changed
        //2 means disabled
        $dayShiftChanged = 0;
        $nightShiftChanged = 0;

        if ($timeOfDay == 'all' || $timeOfDay == 'day') {
            $thisShift = 'day';
            $offday = Offday::date($date)->shift($thisShift)->first();
            if ($offday) {
                $dayShiftChanged = $offday->enabled ? 1 : 2;
            }
        }
        if ($timeOfDay == 'all' || $timeOfDay == 'night') {
            $thisShift = 'night';
            $offday = Offday::date($date)->shift($thisShift)->first();
            if ($offday) {
                $nightShiftChanged = $offday->enabled ? 1 : 2;
            }
        }

        if (($timeOfDay == 'day' && $dayShiftChanged == 2) ||
            ($timeOfDay == 'night' && $nightShiftChanged == 2)
        ) {

            $onlineClosed = true;
        }


        //get lights data
        if ($timeOfDay != 'all') {
            //check if online closed according to stoppeddays
            if (!$onlineClosed) {
                $stopped = StoppedDay::dateShift($date, $timeOfDay)->first();
                if ($stopped) {
                    $red = $stopped->online_stop_num ? $stopped->online_stop_num : $red;

                    if ($stopped->online_closed) {
                        $onlineClosed = true;
                    }
                }
            }

            if ($onlineClosed || $personsNum >= $red) {
                $color = "red";
            } elseif ($personsNum >= $yellow) {
                $color = "orange";
            }
        }

        //get logged changes to stopped days
        $stoppedDayRecords = StoppedDay::dateShift($date, $timeOfDay)->withTrashed()->get();

        $response = array(
            'reservations' => $reservations,
            'persons_num' => $personsNum,
            'online_closed' => $onlineClosed,
            'red' => $red,
            'orange' => $yellow,
            'color' => $color,
            'stopped_day_records' => $stoppedDayRecords,
            'hourly_data' => $hourlyData,
            'day_shift_changed' => $dayShiftChanged,
            'night_shift_changed' => $nightShiftChanged,
            'last' => $currDateTime,
        );
        return $response;

    }

    public function getTablePlanObjectsOnDate($date, $shift)
    {
        $tablePlanSchedule = TablePlanSchedule::getInstance();
        $tablePlan = TablePlan::findOrFail($tablePlanSchedule->getTablePlanIdOnDateAndShift($date, $shift));
        $tablesReserved = Reservation::active()->date($date)->shift($shift)->pluck('table_id')->toArray();

        $response['tables_reserved'] = $tablesReserved;
        $response['table_plan'] = $tablePlan;
        $response['table_plan_objects'] = SectionMapObject::tablePlan($tablePlan->id)->get()->groupBy('section_id');

        return $response;
    }
}
