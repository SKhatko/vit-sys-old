<?php namespace App;

use Carbon\Carbon;
use DB;
use App\Offday;
use \App\Config;

//singleton
class ScheduleSingleton
{

    private static $instance = NULL;

    //working days (booleans)
    public $monday;
    public $tuesday;
    public $wednesday;
    public $thursday;
    public $friday;
    public $saturday;
    public $sunday;

    //reservation hours
    public $monday_times_raw;
    public $tuesday_times_raw;
    public $wednesday_times_raw;
    public $thursday_times_raw;
    public $friday_times_raw;
    public $saturday_times_raw;
    public $sunday_times_raw;

    public $monday_times;
    public $tuesday_times;
    public $wednesday_times;
    public $thursday_times;
    public $friday_times;
    public $saturday_times;
    public $sunday_times;

    public $interval = 15; //15 minutes
    public $daysAhead = 180;// 6 months

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        } else {
            self::$instance = new ScheduleSingleton();
            return self::$instance;
        }
    }

    private function __construct()
    {

        $row = DB::connection('tenant')->select("SELECT * FROM config_schedule");

        $this->monday = $row[0]->monday;
        $this->tuesday = $row[0]->tuesday;
        $this->wednesday = $row[0]->wednesday;
        $this->thursday = $row[0]->thursday;
        $this->wednesday = $row[0]->wednesday;
        $this->thursday = $row[0]->thursday;
        $this->friday = $row[0]->friday;
        $this->saturday = $row[0]->saturday;
        $this->sunday = $row[0]->sunday;

        $this->monday_times_raw = $row[0]->monday_times;
        $this->tuesday_times_raw = $row[0]->tuesday_times;
        $this->wednesday_times_raw = $row[0]->wednesday_times;
        $this->thursday_times_raw = $row[0]->thursday_times;
        $this->friday_times_raw = $row[0]->friday_times;
        $this->saturday_times_raw = $row[0]->saturday_times;
        $this->sunday_times_raw = $row[0]->sunday_times;

        $this->monday_times = $this->monday_times_raw ? unserialize($this->monday_times_raw) : [];
        $this->tuesday_times = $this->tuesday_times_raw ? unserialize($this->tuesday_times_raw) : [];
        $this->wednesday_times = $this->wednesday_times_raw ? unserialize($this->wednesday_times_raw) : [];
        $this->thursday_times = $this->thursday_times_raw ? unserialize($this->thursday_times_raw) : [];
        $this->friday_times = $this->friday_times_raw ? unserialize($this->friday_times_raw) : [];
        $this->saturday_times = $this->saturday_times_raw ? unserialize($this->saturday_times_raw) : [];
        $this->sunday_times = $this->sunday_times_raw ? unserialize($this->sunday_times_raw) : [];

    }

    /*
    * getAvailableTimesForDate
    *
    * returns the available times in an array for specific date. The values will
    * be in number of minutes format.
    *
    * The function looks up for the date in the changed days, and if
    * not found, returns the standard schedule times for this day.
    *
    * Returns empty array in case of error (invalid date)
    * with error info when possible
    *
    */
    public function getAvailableTimesForDate($date, $shift, $personsNum = 0, $online = true)
    {

        $date = date("Y-m-d", strtotime($date));
        $today = date("Y-m-d");
        $maxDate = date("Y-m-d", strtotime($today . " +" . $this->daysAhead . " days"));

        if ($online) {
            if ($date < $today || $date > $maxDate) {
                return ['data' => []];
            }
        }


        $availableTimes = $this->getShiftTimes($date, $shift);


        $offday = Offday::date($date)->shift($shift)->first();
        // If custom day enabled fill up available times variable
        if ($offday) {
            if (!$offday->enabled) {
                return [
                    'data' => [],
                    'error' => 'not_working',
                    'error_msg' => trans('reception.not_working_confirmation_msg'),
                ];
            } else {
                $availableTimes = unserialize($offday->times);
            }
        }


        $stopAt = Config::$red_num;
        $stoppedDay = StoppedDay::date($date)->shift($shift)->first();

        if ($stoppedDay) {
            if ($stoppedDay->online_closed) {
                return [
                    'data' => [],
                    'error' => 'stopped',
                    'error_msg' => trans('reception.closed_confirmation_msg'),
                ];
            } else {
                $stopAt = $stoppedDay->online_stop_num;
            }
        }


        $personsData = Reservation::$shift($date)->active()
            ->select(DB::raw('date, time, SUM(persons_num) as persons'))
            ->groupBy('time')
            ->get();

        $personsCount = $personsData->sum('persons');
        $personsData = $personsData->groupBy('time');

        if ($personsNum + $personsCount >= $stopAt) {
            return [
                'data' => [],
                'error' => 'full',
                'error_msg' => trans('reception.full_confirmation_msg'),
            ];
        }

        $currTime = date("H:i");
        $currMinutes = self::timeToMinutes($currTime);

        $maxHoursToReservation = Config::$max_hours_before_reservation_allowed;
        $minAvailableTime = $maxHoursToReservation * 60 + $currMinutes;

        $maxPersonsPerHour = Config::$max_persons_per_hour;

        if ($maxPersonsPerHour && $personsNum > $maxPersonsPerHour) {
            return [
                'data' => [],
                'error' => 'stopped',
                'error_msg' => trans('reception.closed_confirmation_msg'),
            ];
        }

        foreach ($availableTimes as $key => $time) {

            if ($date == $today && ($time <= $minAvailableTime)) {
                unset($availableTimes[$key]);
                continue;
            }

            if ($maxPersonsPerHour) {
                $personsInPreviousHour = $personsNum;
                $personsInNextHour = $personsNum;
                foreach ($personsData as $timeIndex => $data) {

                    $thisMinutes = $this->timeToMinutes($timeIndex);
                    $minuteDifference = $time - $thisMinutes;

                    if ($minuteDifference >= 0 && $minuteDifference <= 59) {
                        $personsInPreviousHour += $data->sum('persons');
                    } else if ($minuteDifference < 0 && $minuteDifference >= -59) {
                        $personsInNextHour += $data->sum('persons');
                    }

                    if ($personsInPreviousHour > $maxPersonsPerHour || $personsInNextHour > $maxPersonsPerHour) {
                        unset($availableTimes[$key]);
                        break;
                    }
                }
            }

        }

        if (!count($availableTimes)) {
            return [
                'data' => [],
                'error' => 'not_working',
                'error_msg' => trans('reception.not_working_confirmation_msg'),
            ];
        }

        return [
            'data' => array_values($availableTimes),
            'error' => ''
        ];
    }

    public function getShiftTimes($date, $shift)
    {

        //important since we didn't take care of setting NULL on times column
        //when day gets disabled in admin control
        //so day could have times attribute, eventhough day is disabled
        if (!$this->dayOfWeekEnabled($date)) {
            return [];
        }

        $weekDay = strtolower(date("l", strtotime($date)));
        $timesAttribute = $weekDay . '_times';
        $availableTimes = $this->{$timesAttribute};

        $shiftSwap = Config::$day_end;
        $shiftSwapMinutes = $this->timeToMinutes($shiftSwap);

        foreach ($availableTimes as $key => $time) {
            if ($shift == 'day' && (int)$time >= $shiftSwapMinutes) {
                unset($availableTimes[$key]);
            } else if ($shift == 'night' && (int)$time < $shiftSwapMinutes) {
                unset($availableTimes[$key]);
            }
        }

        return $availableTimes;
    }

    public function getDisabledWeekDays()
    {

        $result = array();
        if (!$this->sunday) {
            $result[] = 1;
        }
        if (!$this->monday) {
            $result[] = 2;
        }
        if (!$this->tuesday) {
            $result[] = 3;
        }
        if (!$this->wednesday) {
            $result[] = 4;
        }
        if (!$this->thursday) {
            $result[] = 5;
        }
        if (!$this->friday) {
            $result[] = 6;
        }
        if (!$this->saturday) {
            $result[] = 7;
        }

        return $result;
    }

    public function dayOfWeekEnabled($date)
    {

        $dayOfWeek = date("w", strtotime($date));

        if (in_array($dayOfWeek + 1, $this->getDisabledWeekDays())) {
            //+1 since disabledWeekDays are from 1 to 7 instead of 0 to 6
            return false;
        }
        return true;
    }

    public function getDisabledDates($minDate, $maxDate, $shift, $system = false, $personsNum = 0)
    {

        $minDate = date("Y-m-d", strtotime($minDate));
        $maxDate = date("Y-m-d", strtotime($maxDate));
        $today = date("Y-m-d");

        $shiftSwap = Config::$day_end;
        $shiftSwapMinutes = $this->timeToMinutes($shiftSwap);

        $offdays = Offday::shift($shift)->from($minDate)->to($maxDate)->orderBy('date')->get();
        $offdays = $offdays->groupBy('date');

        $stoppedDays = StoppedDay::shift($shift)->from($minDate)->to($maxDate)->orderBy('date')->get();
        $stoppedDays = $stoppedDays->groupBy('date');

        $personsData = Reservation::from($minDate)->to($maxDate)->shift($shift)->active()
            ->select(DB::raw('date, time, SUM(persons_num) as persons'))
            ->groupBy('date')
            ->get();
        $personsData = $personsData->groupBy('date');

        //we will return this array
        $disabledDates = [];
        $thisDate = $minDate;
        while ($thisDate <= $maxDate) {

            $dateIsGood = true;
            $reason = null; //'disabled' / 'closed'

            if (!count($this->getShiftTimes($thisDate, $shift))) {
                $dateIsGood = false;
                $reason = 'disabled';
            }

            if (!$system && $thisDate < $today) {
                $dateIsGood = false;
                $reason = 'disabled';
            } else if (!$system && $thisDate == $today) {

                $availableTimes = $this->getAvailableTimesForDate($thisDate, $shift, $personsNum, !$system); //we call this only on today
                if (!count($availableTimes['data'])) {
                    $dateIsGood = false;
                    if ($reason == null) {
                        $reason = 'closed';
                    }
                } else {
                    $dateIsGood = true;
                    $reason = null;
                }
            } else {

                //check offday
                if (isset($offdays[$thisDate])) {
                    $offday = $offdays[$thisDate][0];
                    if (!$offday->enabled) {
                        $dateIsGood = false;
                        $reason = 'disabled';
                    } else {
                        $dateIsGood = true;
                        $reason = null;
                    }
                }

                //check stopped days
                $stopAt = Config::$red_num;
                if ($dateIsGood && isset($stoppedDays[$thisDate])) {
                    $stoppedDay = $stoppedDays[$thisDate][0];
                    if ($stoppedDay->online_closed) {
                        $dateIsGood = false;
                        $reason = 'closed';
                    } else {
                        $stopAt = $stoppedDay->online_stop_num;
                    }
                }


                //check persons count
                if ($dateIsGood && isset($personsData[$thisDate])) {
                    $personsCount = $personsData[$thisDate][0]->persons;
                    if ($personsCount + $personsNum >= $stopAt) {
                        $dateIsGood = false;
                        $reason = 'closed';
                    }
                }
            }

            if (!$dateIsGood) {
                if ($system) {
                    $disabledDates[$thisDate] = $reason;
                } else {
                    $disabledDates[] = $thisDate;
                }
            }

            $thisDate = date("Y-m-d", strtotime($thisDate . ' +1 day'));
        }

        return $disabledDates;
    }

    public function updateSchedule($data)
    {
        DB::connection('tenant')->table('config_schedule')->update($data);
    }

    public static function formatMinutes($minutes)
    {
        $hour = floor($minutes / 60);
        if ($hour < 10) {
            $hour = "0" . $hour;
        }

        $mins = $minutes % 60;
        if ($mins < 10) {
            $mins = "0" . $mins;
        }

        return $hour . ':' . $mins;
    }

    public static function timeToMinutes($time)
    {
        $hr = date("H", strtotime($time));
        $mins = date("i", strtotime($time));

        return $hr * 60 + $mins;
    }

    public static function minutesToTime($minutes)
    {
        return strtotime(date('H:i:s', mktime(0, $minutes, 0)));
    }

    public function getDailySchedule()
    {
        return [
            'monday' => [
                'schedule' => $this->monday_times,
                'enabled' => $this->monday
            ],
            'tuesday' => [
                'schedule' => $this->tuesday_times,
                'enabled' => $this->tuesday
            ],
            'wednesday' => [
                'schedule' => $this->wednesday_times,
                'enabled' => $this->wednesday
            ],
            'thursday' => [
                'schedule' => $this->thursday_times,
                'enabled' => $this->thursday
            ],
            'friday' => [
                'schedule' => $this->friday_times,
                'enabled' => $this->friday
            ],
            'saturday' => [
                'schedule' => $this->saturday_times,
                'enabled' => $this->saturday
            ],
            'sunday' => [
                'schedule' => $this->sunday_times,
                'enabled' => $this->sunday
            ]
        ];
    }

}