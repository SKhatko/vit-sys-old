<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

define("DEFAULT_MONTHS", 6);

class AnalyticsController extends Controller
{
    public function reservations()
    {

        /*
        $title = trans('analytics.reservations_data');
        $pageName = 'reservations';

        return view('analytics.reservations')->with([
            'title'		=>	$title,
            'pageName'	=>	$pageName,
        ]);
        */

    }

    public function reservationsRefReport()
    {

        $title = trans('analytics.reservations_ref_report');
        $pageName = 'reservations_ref_report';

        $defaultFrom = date("Y-m-d", strtotime("-30 days"));
        $defaultTo = date("Y-m-d", strtotime("yesterday"));

        $from = session()->has('analytics.reservations.ref.date_from') ? session()->get('analytics.reservations.ref.date_from') : $defaultFrom;
        $to = session()->has('analytics.reservations.ref.date_to') ? session()->get('analytics.reservations.ref.date_to') : $defaultTo;

        return view('analytics.reservations.ref_report')->with([
            'title' => $title,
            'pageName' => $pageName,

            'dateFrom' => $from,
            'dateTo' => $to,
        ]);
    }

    public function setReservationsSourceReportPeriod(Request $request)
    {

        $dateRange = $request->input('date_range');
        $dateRange = explode(' - ', $dateRange);

        if (count($dateRange) == 2) {
            $start = $dateRange[0];
            $end = $dateRange[1];

            session()->put('analytics.reservations.ref.date_from', $start);
            session()->put('analytics.reservations.ref.date_to', $end);
        }

        return redirect()->action('AnalyticsController@reservationsRefReport');
    }

    public function reservationsSourceData($from = NULL, $to = NULL)
    {

        if (!isset($from) || !$from) {
            $from = date("Y-m-d", strtotime("-30 days"));
        } else {
            $from = date("Y-m-d", strtotime($from));
        }

        if (!isset($to) || !$to) {
            $to = date("Y-m-d", strtotime("yesterday"));
        } else {
            $to = date("Y-m-d", strtotime($to));
        }

        $yesterday = date("Y-m-d", strtotime("yesterday"));

        if ($from > $to) {
            $from = date("Y-m-d", strtotime("-30 days"));
            $to = $yesterday;
        }

        $data = [];

        $rawData = DB::connection('tenant')->table('reservations')
            ->select(DB::raw("count(*) as COUNT, sum(persons_num) as PERSONS, ref"))
            ->where('source', '=', 'online')->where('date', '>=', $from)->where('date', '<=', $to)
            ->where('status_id', '=', 1)
            ->whereNull('deleted_at')
            ->groupBy('ref')
            ->get();

        foreach ($rawData as $row) {
            if (!$row->ref) {
                $data[trans('analytics.unknown')]['reservations'] = $row->COUNT;
                $data[trans('analytics.unknown')]['persons'] = $row->PERSONS;
            } else {
                $data[$row->ref]['reservations'] = $row->COUNT;
                $data[$row->ref]['persons'] = $row->PERSONS;
            }
        }

        return $data;
    }

    public function reservationsMonthlyStats()
    {

        $title = trans('analytics.reservations_monthly_stats');
        $pageName = 'reservations_monthly_stats';

        $months = session()->has('analytics.reservations.monthly.months') ? session()->get('analytics.reservations.monthly.months') : DEFAULT_MONTHS;

        return view('analytics.reservations.monthly_stats')->with([
            'title' => $title,
            'pageName' => $pageName,
            'months' => $months,
        ]);
    }

    public function reservationsMonthlyStatsData($months = DEFAULT_MONTHS)
    {

        $data = [];

        $yesterday = date("Y-m-d", strtotime("yesterday"));
        $months -= 1;
        $startDate = date("Y-m-01", strtotime("-" . $months . " months"));

        $rawData = DB::connection('tenant')->table('reservations')
            ->select(DB::raw("count(*) as COUNT, 
				sum(persons_num) as PERSONS, 
				sum(is_walkin) as WALK_INS, 
				sum(if(is_walkin=1, persons_num, 0)) as WALK_INS_PERSONS,
				sum(showed) as SHOWED, 
				sum(if(showed=1, persons_num, 0)) as SHOWED_PERSONS,
				sum(source='online') as ONLINE, 
				sum(if(source='online', persons_num, 0)) as ONLINE_PERSONS,
				DATE_FORMAT(date, '%Y-%m-01') as MONTH"))
            ->where('status_id', '=', 1)
            ->where('date', '>=', $startDate)->where('date', '<=', $yesterday)
            ->whereNull('deleted_at')
            ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m-01")'))
            ->orderBy('date')
            ->get();

        foreach ($rawData as $row) {
            $month = date("Y-m", strtotime($row->MONTH));
            $data[$month] = [
                'reservations_num' => $row->COUNT,
                'persons_num' => $row->PERSONS,
                'walk_ins' => $row->WALK_INS,
                'walk_ins_persons' => $row->WALK_INS_PERSONS,
                'showed' => $row->SHOWED,
                'showed_persons' => $row->SHOWED_PERSONS,
                'online' => $row->ONLINE,
                'online_persons' => $row->ONLINE_PERSONS,
            ];
        }

        return $data;
    }


    public function reservationsDailyStats()
    {

        $title = trans('analytics.reservations_daily_stats');
        $pageName = 'reservations_daily_stats';

        $defaultFrom = date("Y-m-d", strtotime("-30 days"));
        $defaultTo = date("Y-m-d", strtotime("yesterday"));

        $from = session()->has('analytics.reservations.daily.date_from') ? session()->get('analytics.reservations.daily.date_from') : $defaultFrom;
        $to = session()->has('analytics.reservations.daily.date_to') ? session()->get('analytics.reservations.daily.date_to') : $defaultTo;

        return view('analytics.reservations.daily_stats')->with([
            'title' => $title,
            'pageName' => $pageName,

            'dateFrom' => $from,
            'dateTo' => $to,
        ]);
    }

    public function reservationsDailyStatsData($from = NULL, $to = NULL)
    {

        if (!isset($from) || !$from) {
            $from = date("Y-m-d", strtotime("-30 days"));
        } else {
            $from = date("Y-m-d", strtotime($from));
        }

        if (!isset($to) || !$to) {
            $to = date("Y-m-d", strtotime("yesterday"));
        } else {
            $to = date("Y-m-d", strtotime($to));
        }

        $yesterday = date("Y-m-d", strtotime("yesterday"));

        if ($from > $to) {
            $from = date("Y-m-d", strtotime("-30 days"));
            $to = $yesterday;
        }

        $data = [];

        //fill data with zeros as default
        $thisDate = $from;
        while ($thisDate <= $to) {
            $data[$thisDate] = [
                'reservations_num' => 0,
                'persons_num' => 0,
                'walk_ins' => 0,
                'walk_ins_persons' => 0,
                'showed' => 0,
                'showed_persons' => 0,
                'online' => 0,
                'online_persons' => 0,
            ];

            $thisDate = date("Y-m-d", strtotime($thisDate . ' +1 day'));
        }

        $rawData = DB::connection('tenant')->table('reservations')
            ->select(DB::raw("count(*) as COUNT, 
				sum(persons_num) as PERSONS, 
				sum(is_walkin) as WALK_INS, 
				sum(if(is_walkin=1, persons_num, 0)) as WALK_INS_PERSONS,
				sum(showed) as SHOWED, 
				sum(if(showed=1, persons_num, 0)) as SHOWED_PERSONS,
				sum(source='online') as ONLINE, 
				sum(if(source='online', persons_num, 0)) as ONLINE_PERSONS,
				date"))
            ->where('status_id', '=', 1)->where('date', '>=', $from)->where('date', '<=', $to)
            ->whereNull('deleted_at')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($rawData as $row) {
            $date = date("Y-m-d", strtotime($row->date));
            $data[$date] = [
                'reservations_num' => $row->COUNT,
                'persons_num' => $row->PERSONS,
                'walk_ins' => $row->WALK_INS,
                'walk_ins_persons' => $row->WALK_INS_PERSONS,
                'showed' => $row->SHOWED,
                'showed_persons' => $row->SHOWED_PERSONS,
                'online' => $row->ONLINE,
                'online_persons' => $row->ONLINE_PERSONS,
            ];
        }

        return $data;
    }

    public function setReservationsDailyReportDateRange(Request $request)
    {

        $dateRange = $request->input('date_range');
        $dateRange = explode(' - ', $dateRange);

        if (count($dateRange) == 2) {
            $start = $dateRange[0];
            $end = $dateRange[1];

            session()->put('analytics.reservations.daily.date_from', $start);
            session()->put('analytics.reservations.daily.date_to', $end);
        }

        return redirect()->action('AnalyticsController@reservationsDailyStats');
    }

    public function setReservationsMonthlyPeriod(Request $request)
    {

        $months = $request->input('months');
        if ($months && is_numeric($months)) {
            session()->put('analytics.reservations.monthly.months', $months);
        }

        return redirect()->action('AnalyticsController@reservationsMonthlyStats');
    }

    public function setStatusesMonthlyPeriod(Request $request)
    {

        $months = $request->input('months');
        if ($months && is_numeric($months)) {
            session()->put('analytics.reservations.statuses.months', $months);
        }

        return redirect()->action('AnalyticsController@reservationStatuses');
    }

    public function reservationStatuses()
    {

        $title = trans('analytics.reservation_statuses_report');
        $pageName = 'reservation_statuses_report';

        $months = session()->has('analytics.reservations.statuses.months') ? session()->get('analytics.reservations.statuses.months') : DEFAULT_MONTHS;

        return view('analytics.reservations.statuses')->with([
            'title' => $title,
            'pageName' => $pageName,
            'months' => $months,
        ]);
    }

    public function reservationStatusesData($months = DEFAULT_MONTHS)
    {

        $data = [];

        $yesterday = date("Y-m-d", strtotime("yesterday"));

        $months -= 1;
        $startDate = date("Y-m-01", strtotime("-" . $months . " months"));

        $rawData = DB::connection('tenant')->table('reservations')
            ->select(DB::raw("count(*) as TOTAL, 
				sum(persons_num) as PERSONS,
				sum(status_id=1) as NORMAL, 
				sum(if(status_id=1, persons_num, 0)) as NORMAL_PERSONS,
				sum(status_id=2) as CANCELLED,
				sum(if(status_id=2, persons_num, 0)) as CANCELLED_PERSONS, 
				sum(status_id=3) as NOSHOW, 
				sum(if(status_id=3, persons_num, 0)) as NOSHOW_PERSONS,
				sum(status_id=4) as WAITING, 
				sum(if(status_id=4, persons_num, 0)) as WAITING_PERSONS,
				DATE_FORMAT(date, '%Y-%m-01') as MONTH"))
            ->where('date', '>=', $startDate)->where('date', '<=', $yesterday)
            ->whereNull('deleted_at')
            ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m-01")'))
            ->orderBy('date')
            ->get();

        foreach ($rawData as $row) {
            $month = date("Y-m", strtotime($row->MONTH));
            $data[$month] = [
                'total' => $row->TOTAL,
                'persons' => $row->PERSONS,
                'normal' => $row->NORMAL,
                'normal_persons' => $row->NORMAL_PERSONS,
                'cancelled' => $row->CANCELLED,
                'cancelled_persons' => $row->CANCELLED_PERSONS,
                'noshow' => $row->NOSHOW,
                'noshow_persons' => $row->NOSHOW_PERSONS,
                'waiting' => $row->WAITING,
                'waiting_persons' => $row->WAITING_PERSONS
            ];
        }

        return $data;
    }
}
