<?php namespace App\Http\Controllers\Online;


use App\Reservation;
use App\ReservationStatus;
use App\Client;
use App\ScheduleSingleton;
use App\Config as TenantConfig;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Mail;
use Session;

class ReservationsController extends Controller
{

    function getLastAvailableStep()
    {

        $step = 1;

        if (session()->has('online.step1') && session()->get('online.step1')) {
            $step = 2;
        }

        if (session()->has('online.step2') && session()->get('online.step2')) {
            $step = 3;
        }

        return $step;
    }

    private function handleRefParameter()
    {

        if (isset($_GET['ref'])) {
            session()->put('online.ref', $_GET['ref']);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function step1()
    {

        if (session()->has('online.reservation_identifier')) {
            return redirect()->action('Online\ReservationsController@completionPage');
        }

        $this->handleRefParameter();

        $schedule = ScheduleSingleton::getInstance();

        $minDate = date("Y-m-d");
        $maxDate = date("Y-m-d", strtotime("+" . $schedule->daysAhead . " days"));

        //$disabledWeekDays = $schedule->getDisabledWeekDays();
        $nightDisabledDates = $schedule->getDisabledDates($minDate, $maxDate, 'night');
        $dayDisabledDates = $schedule->getDisabledDates($minDate, $maxDate, 'day');
        
        $selectedPersonsNum = session()->has('online.persons_num') ? session()->get('online.persons_num') : NULL;
        $selectedDate = session()->has('online.date') ? session()->get('online.date') : NULL;
        $selectedMinutes = session()->has('online.minutes') ? session()->get('online.minutes') : NULL;
        $selectedTimeOfDay = session()->has('online.time_of_day') ? session()->get('online.time_of_day') : NULL;

        $config = new TenantConfig();
        $maxPersons = $config::$max_online_persons;

        $personsArray = [];
        $personsArray[1] = "1 " . trans('general.person_one_prefixed');
        for ($i = 2; $i <= $maxPersons; $i++) {
            $personsArray[$i] = $i . " " . trans('general.persons');
        }

        $lastAvailableStep = $this->getLastAvailableStep();

        $dayStart = date("H:i", strtotime(\App\Config::$day_start));
        $dayEnd = date("H:i", strtotime(\App\Config::$day_end));
        $nightEnd = date("H:i", strtotime(\App\Config::$night_end));

        $timesOfDayArray = [
            '' => '',
            'day' => trans('general.day') . ' (' . $dayStart . ' - ' . $dayEnd . ')',
            'night' => trans('general.night') . ' (' . $dayEnd . ' - ' . $nightEnd . ')'
        ];

        return view('online.reservation.step1')->with([
            'step' => 1,
            'minDate' => $minDate,
            'maxDate' => $maxDate,

            'nightDisabledDates' => $nightDisabledDates,
            'dayDisabledDates' => $dayDisabledDates,

            'selectedPersonsNum' => $selectedPersonsNum,
            'selectedDate' => $selectedDate,
            'selectedMinutes' => $selectedMinutes,
            'selectedTimeOfDay' => $selectedTimeOfDay,
            'timesOfDayArray' => $timesOfDayArray,
            'personsArray' => $personsArray,

            'lastAvailableStep' => $lastAvailableStep,
        ]);
    }

    public function postStep1(Request $request)
    {

        $this->validate($request, [
            'persons_num' => 'required|numeric',
            'time_of_day' => 'required',
            'time' => 'required',
            'date' => 'required'
        ]);

        //check if time value is valid number
        $time = (int)$request->input('time');
        if ($time < 0 || $time > 1440) {

            session()->flash('flash_message', 'Please choose valid time.');
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }

        $date = $request->input('date');

        session()->put('online.persons_num', (int)$request->input('persons_num'));
        session()->put('online.time_of_day', $request->input('time_of_day'));
        session()->put('online.date', $date);
        session()->put('online.minutes', $time);
        session()->put('online.time', ScheduleSingleton::getInstance()->formatMinutes($time));
        session()->put('online.step1', true);

        $this->checkTimeAvailability();

        return redirect()->action('Online\ReservationsController@step2');
    }

    public function step2()
    {

        if (session()->has('online.reservation_identifier')) {
            return redirect()->action('Online\ReservationsController@completionPage');
        }

        $this->handleRefParameter();

        if (!session()->has('online.step1')) {
            return redirect()->action('Online\ReservationsController@step1');
        }

        $personsNum = session()->get('online.persons_num');
        $selectedDate = session()->get('online.date');
        $selectedTime = session()->get('online.time');

        $honorific = session()->has('online.honorific') ? session()->get('online.honorific') : NULL;
        $firstName = session()->has('online.first_name') ? session()->get('online.first_name') : NULL;
        $lastName = session()->has('online.last_name') ? session()->get('online.last_name') : NULL;
        $email = session()->has('online.email') ? session()->get('online.email') : NULL;
        $phoneCode = session()->has('online.phone_code') ? session()->get('online.phone_code') : NULL;
        $phoneNum = session()->has('online.phone_num') ? session()->get('online.phone_num') : NULL;
        $mobile = session()->has('online.mobile') ? session()->get('online.mobile') : NULL;
        $notes = session()->has('online.notes') ? session()->get('online.notes') : NULL;

        $lastAvailableStep = $this->getLastAvailableStep();

        return view('online.reservation.step2')->with([
            'step' => 2,
            'personsNum' => $personsNum,
            'selectedDate' => $selectedDate,
            'selectedTime' => $selectedTime,

            'honorific' => $honorific,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phoneCode' => $phoneCode,
            'mobile' => $mobile,
            'phoneNum' => $phoneNum,
            'notes' => $notes,

            'lastAvailableStep' => $lastAvailableStep
        ]);
    }

    public function postStep2(Request $request)
    {

        if (!session()->has('online.step1')) {
            return redirect()->action('Online\ReservationsController@step1');
        }

        $this->validate($request, [
            'honorific' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required'
        ]);

        session()->put('online.honorific', $request->input('honorific'));
        session()->put('online.first_name', $request->input('first_name'));
        session()->put('online.last_name', $request->input('last_name'));
        session()->put('online.name', $request->input('first_name') . ' ' . $request->input('last_name'));
        session()->put('online.email', $request->input('email'));
        session()->put('online.phone_num', $request->input('phone_num'));
        session()->put('online.mobile', $request->input('mobile'));
        session()->put('online.notes', $request->input('notes'));
        session()->put('online.step2', true);

        $this->checkTimeAvailability();

        return redirect()->action('Online\ReservationsController@step3');
    }

    public function step3()
    {

        if (session()->has('online.reservation_identifier')) {
            return redirect()->action('Online\ReservationsController@completionPage');
        }

        $this->handleRefParameter();

        if (!session()->has('online.step1')) {
            return redirect()->action('Online\ReservationsController@step1');
        }
        if (!session()->has('online.step2')) {
            return redirect()->action('Online\ReservationsController@step2');
        }

        $lastAvailableStep = $this->getLastAvailableStep();

        return view('online.reservation.step3')->with([
            'step' => 3,

            'lastAvailableStep' => $lastAvailableStep
        ]);
    }

    public function duplicate()
    {

        return view('online.reservation.duplicate');

        session()->forget('online');
    }

    public function postStep3(Request $request)
    {

        $this->checkTimeAvailability();

        if (!session()->has('online.step1')) {
            return redirect()->action('Online\ReservationsController@step1');
        }
        if (!session()->has('online.step2')) {
            return redirect()->action('Online\ReservationsController@step2');
        }

        //make sure today is not stopped


        //create client if doesn't exist
        $honorific = session()->get('online.honorific');
        $gender = $honorific == 'mr' ? 'male' : 'female';

        $firstName = session()->get('online.first_name');
        $lastName = session()->get('online.last_name');
        $phoneNum = session()->get('online.phone_num');
        $mobile = session()->get('online.mobile');
        $email = session()->get('online.email');

        $clientData = [
            'gender' => $gender,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phoneNum,
            'mobile' => $mobile,
            'email' => $email,
            'restaurant_newsletter' => $request->input('restaurant_newsletter') ? true : false,
            'vitisch_newsletter' => $request->input('vitisch_newsletter') ? true : false,
        ];

        $time = ScheduleSingleton::formatMinutes(session()->get('online.minutes'));
        $date = date("Y-m-d", strtotime(session()->get('online.date')));


        //$reservationDateTime = date("Y-m-d H:i:s", strtotime($date.' '.$time));

        //$client = Client::where('mobile', '=', $mobile)->orWhere('phone', '=', $phoneNum)->orWhere('email', '=', $email)->first();

        $client = Client::where(function ($query) use ($mobile) {
            $query->whereNotNull('mobile')->where('mobile', '!=', '')->where('mobile', '=', $mobile);
        })->orWhere(function ($query) use ($phoneNum) {
            $query->whereNotNull('phone')->where('phone', '!=', '')->where('phone', '=', $phoneNum);
        })->orWhere(function ($query) use ($email) {
            $query->whereNotNull('email')->where('email', '!=', '')->where('email', '=', $email);
        })->first();

        if (!$client) {
            $client = Client::where('first_name', 'LIKE', $firstName)->where('last_name', 'LIKE', $lastName)->first();
        }

        if (!$client) {
            $client = Client::create($clientData);
        } else {
            $client->update($clientData);
        }

        //check duplicate
        $existing = Reservation::where('persons_num', session()->get('online.persons_num'))
            ->where('client_id', $client->id)
            ->where('date', $date)
            ->where('time', $time)
            ->first();

        if ($existing) {
            return redirect()->action('Online\ReservationsController@duplicate');
        }

        $ref = session()->has('online.ref') ? session()->get('online.ref') : NULL;

        //make reservations, and redirect to confirmation page
        $data = [
            //'name'		=>	$name,
            'phone' => $phoneNum,
            'persons_num' => session()->get('online.persons_num'),
            'client_id' => $client->id,
            'date' => $date,
            'time' => $time,
            'description' => session()->get('online.notes'),
            'source' => 'online',
            'ref' => $ref
        ];

        $reservation = Reservation::create($data);

        session()->put('online.reservation_identifier', $reservation->identifier);


        $cancelToken = hash('sha512', $reservation->identifier . '%*' . $reservation->id);

        $reservation->url_token = $cancelToken;
        $reservation->save();

        try {
            $this->sendEmails($cancelToken);
        } catch (\Swift_TransportException $e) {
            //log error
            //@TODO
        } finally {
            //Log error
            //@TODO
        }

        return redirect()->action('Online\ReservationsController@completionPage');
    }

    public function checkTimeAvailability()
    {

        //set session message

        $time = session()->get('online.minutes');
        $date = session()->get('online.date');
        $shift = session()->get('online.time_of_day');

        $availableTimes = ScheduleSingleton::getInstance()->getAvailableTimesForDate($date, $shift);

        if (!in_array($time, $availableTimes['data'])) {

            session()->forget('online.date');
            session()->forget('online.minutes');
            session()->forget('online.time');
            session()->forget('online.step1');

            session()->put('flash_message', trans('online.date_time_no_longer_available_msg'));
            session()->put('flash_message_type', 'alert-danger');

            return redirect()->action('Online\ReservationsController@step1');
        }
    }

    public function sendEmails($cancelToken)
    {

        $time = ScheduleSingleton::formatMinutes(session()->get('online.minutes'));
        $date = session()->get('online.date');

        $thisTime = strtotime($date . ' ' . $time);
        $reservationDate = date("d-m-Y", $thisTime);
        $reservationTime = date("H:i", $thisTime);
        $weekDay = strtolower(date("l", $thisTime));
        $weekDay = trans('general.' . $weekDay);
        $reservationDateTime = $weekDay . ' ' . $reservationDate . ', ' . $reservationTime . ' ' . trans('online.time_suffix');

        $phoneNum = (session()->has('online.phone_num') && session()->get('online.phone_num')) ? session()->get('online.phone_num') : NULL;

        $fullName = trans('crm.honorific_' . session()->get('online.honorific'));
        $fullName .= ' ' . session()->get('online.first_name');
        $fullName .= ' ' . session()->get('online.last_name');

        $ref = session()->has('online.ref') ? session()->get('online.ref') : NULL;

        $emailData = [
            'restaurantName' => TenantConfig::$restaurant_name,
            'restaurantEmail' => TenantConfig::$email,

            'firstName' => session()->get('online.first_name'),
            'lastName' => session()->get('online.last_name'),
            'honorific' => session()->get('online.honorific'),
            'name' => $fullName,
            'email' => session()->get('online.email'),
            'mobile' => session()->get('online.mobile'),
            'phoneNum' => $phoneNum,

            'cancelToken' => $cancelToken,

            'referenceId' => strtoupper(session()->get('online.reservation_identifier')),
            'time' => $reservationDateTime,
            'personsNum' => session()->get('online.persons_num'),
            'notes' => session()->get('online.notes'),
            'ref' => $ref,
        ];

        //return view('emails.admin_reservation_email')->with($emailData);

        /*** Admin Email ***/
        $adminEmail = $emailData['restaurantEmail'];
        if ($adminEmail && !filter_var($adminEmail, FILTER_VALIDATE_EMAIL) === false) {
            $subject = trans('online.new_online_reservation') . ' - ' . $emailData['referenceId'];
            Mail::send('emails.admin_reservation_email', $emailData, function ($message) use ($emailData, $subject, $adminEmail) {
                $message->to($adminEmail, $emailData['restaurantName'])->subject($subject);
            });
        }

        /*** Client Email ***/
        $subject = trans('online.online_reservation_at') . ': ' . $emailData['restaurantName'] . ' - ' . $reservationDateTime;
        Mail::send('emails.client_reservation_confirmation_email', $emailData, function ($message) use ($emailData, $subject) {
            $message->to($emailData['email'], $emailData['name'])->subject($subject);
        });
    }

    public function completionPage()
    {

        if (!session()->has('online.reservation_identifier')) {
            return redirect()->action('Online\ReservationsController@step1');
        }

        return view('online.reservation.success')->with([
            'step' => 'success'
        ]);
    }

    public function cancelReservation($referenceId, $cancelToken)
    {

        $reservation = Reservation::identifier($referenceId)->first();

        if (!$reservation || strcmp($reservation->url_token, $cancelToken)) {
            return view('online.reservation.cancel')->with([
                'error' => true,
                'complete' => false,
                'reason' => 'invalid_parameters'
            ]);
        }

        $cancelledStatus = ReservationStatus::where('name', '=', 'cancelled')->first();
        if ($reservation->status_id == $cancelledStatus->id) {
            return view('online.reservation.cancel')->with([
                'error' => true,
                'complete' => false,
                'reason' => 'already_cancelled'
            ]);
        }

        $currDateTime = date("Y-m-d H:i:s");
        $reservationTime = date("Y-m-d H:i:s", strtotime($reservation->date . ' ' . $reservation->time));

        if ($reservationTime <= $currDateTime) {
            return view('online.reservation.cancel')->with([
                'error' => true,
                'complete' => false,
                'reason' => 'too_late'
            ]);
        }

        return view('online.reservation.cancel')->with([
            'error' => false,
            'complete' => false,
            'referenceId' => $referenceId,
            'cancelToken' => $cancelToken,
        ]);
    }

    public function postCancelReservation(Request $request)
    {

        $this->validate($request, [
            'reference_id' => 'required',
            'cancel_token' => 'required',
        ]);

        $referenceId = $request->input('reference_id');
        $cancelToken = $request->input('cancel_token');

        $reservation = Reservation::identifier($referenceId)->first();

        if (!$reservation || strcmp($reservation->url_token, $cancelToken)) {
            return view('online.reservation.cancel')->with([
                'error' => true,
                'complete' => false,
                'reason' => 'invalid_parameters'
            ]);
        }

        $currDateTime = date("Y-m-d H:i:s");
        $reservationTime = date("Y-m-d H:i:s", strtotime($reservation->date . ' ' . $reservation->time));

        if ($reservationTime <= $currDateTime) {
            return view('online.reservation.cancel')->with([
                'error' => true,
                'complete' => false,
                'reason' => 'too_late'
            ]);
        }

        $cancelledStatus = ReservationStatus::where('name', '=', 'cancelled')->first();
        $reservation->status_id = $cancelledStatus->id;

        $reservation->save();
        //$this->logChange($request, $id, 'active');

        return view('online.reservation.cancel')->with([
            'error' => false,
            'complete' => true
        ]);
    }

    public function clearSession()
    {

        $ref = session()->has('online.ref') ? session()->get('online.ref') : NULL;

        session()->forget('online');

        if ($ref) {
            session()->put('online.ref', $ref);
        }

        return redirect()->action('Online\ReservationsController@step1');
    }

    /*
    public function validateCancelParameters($referenceId, $cancelToken) {

    }
    */

    //ajax
    public function getTimesForDate($date, $shift, $personsNum = NULL, $online = true)
    {

        return ScheduleSingleton::getInstance()->getAvailableTimesForDate($date, $shift, $personsNum, $online);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
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
