<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ScheduleSingleton;
use App\Config as TenantConfig;
use App\Reservation;
use App\ClientStatus;
use App\EventType;
use App\OfferStatus;
use App\Misc;
use App\Section;
use App\MenuCart;

class PreordersController extends Controller
{
    public function index()
    {

        $title = trans('kitchen.preorders');

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

        $reservations = Reservation::with('client')->active()->date($filterDate)->orderBy('time')->get();
        $cancelled = Reservation::with('client')->cancelled()->date($filterDate)->orderBy('time')->get();
        $noshow = Reservation::with('client')->noshow()->date($filterDate)->orderBy('time')->get();

        $clientStatusesRaw = ClientStatus::all();
        $clientStatuses = [];
        foreach ($clientStatusesRaw as $status) {
            $clientStatuses[$status->id] = [
                'name' => $status->name,
                'text' => trans('crm.' . $status->name)
            ];
        }

        $eventTypesRaw = EventType::all();
        $eventTypes = [];
        foreach ($eventTypesRaw as $eventType) {
            $eventTypes[$eventType->id] = [
                'name' => $eventType->name,
                'text' => trans('reception.' . $eventType->name)
            ];
        }

        $offerStatusesRaw = OfferStatus::all();
        $offerStatuses = [];
        foreach ($offerStatusesRaw as $offerStatus) {
            $offerStatuses[$offerStatus->id] = [
                'name' => $offerStatus->name,
                'text' => trans('reception.' . $offerStatus->name)
            ];
        }

        $sections = Section::ordered()->pluck('name', 'id')->all();


        return view('kitchen.preorders.index')->with([
            'title' => $title,
            'dayStart' => $dayStart,
            'nightEnd' => $nightEnd,
            'sections' => $sections,
            'reservations' => $reservations,
            'cancelled' => $cancelled,
            'noshow' => $noshow,
            'filterDate' => $filterDate,
            'filterTimeOfDay' => $filterTimeOfDay,

            'clientStatuses' => $clientStatuses,
            'eventTypes' => $eventTypes,
            'offerStatuses' => $offerStatuses,

            'badNightDates' => $badNightDates,
            'badDayDates' => $badDayDates
        ]);
    }

    public function show($reservationId)
    {

        $title = trans('kitchen.preorders');
        $pageName = "preorders_show";

        $reservation = Reservation::with(['preorders.items', 'preorders.groups'])->findOrFail($reservationId);

        $preordersSummary = Misc::getPreordersSummary($reservation->preorders);

        $language = Misc::getPreordersMenuLanguage();

        return view('kitchen.preorders.show')->with([
            'title' => $title,
            'reservationId' => $reservationId,
            'preorders' => $reservation->preorders,
            'preordersSummary' => $preordersSummary,
            'language' => $language
        ]);
    }

    public function menu($reservationId)
    {

        $reservation = Reservation::findOrFail($reservationId);

        $identifier = $reservation->identifier;
        $token = $reservation->url_token;

        session()->forget('preorder');

        session()->put('preorder.identifier', $identifier);
        session()->put('preorder.reservation_id', $reservationId);
        session()->put('preorder.token', $token);
        session()->put('preorder.internal', true);

        MenuCart::clearCart();

        return redirect()->action('Online\PreordersController@menu');
    }

}
