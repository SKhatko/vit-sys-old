<?php namespace App\Http\Controllers;

use App\Offday;
use App\Config as TenantConfig;
use App\ScheduleSingleton;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class OffdaysController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('admin.off_days');
        $pageName = "off-days";


        $offdays = Offday::orderBy('date', 'desc')->paginate(30);

        return view('admin.offdays.index')->with([
            'title' => $title,
            'pageName' => $pageName,

            'offdays' => $offdays
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('admin.new_offday');
        $pageName = "off-days";

        $config = new TenantConfig();
        $schedule = ScheduleSingleton::getInstance();

        return view('admin.offdays.create')->with([
            'title' => $title,
            'pageName' => $pageName,

            'config' => $config,
            'schedule' => $schedule,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'shift' => 'required',
        ]);

        if (!$request->input('times') || !is_array($request->input('times')) || !count($request->input('times'))) {
            //redirect back with error
            session()->flash('flash_message', 'Error. Invalid input. no times detected');
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }

        $existing = Offday::date($request->input('date'))->shift($request->input('shift'))->first();

        if ($existing) {
            $existing->delete();
        }

        $offday = Offday::create($request->all());

        if ($offday->enabled) {
            $times = $request->input('times');
            $offday->times = $times;
            $offday->save();
        }

        session()->flash('flash_message', trans('admin.offday_created_success_msg_with_date_and_shift', ['date' => '<strong>' . $offday->date . '</strong>', 'shift' => '<strong>' . trans('general.' . $offday->shift) . '</strong>']));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('ConfigController@reservationHours');
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
        $offday = Offday::findOrFail($id);

        $title = trans('admin.edit_offday_with_date', ['date' => $offday->date]);
        $config = new TenantConfig();
        $schedule = ScheduleSingleton::getInstance();

        return view('admin.offdays.edit')->with([
            'offday' => $offday,
            'title' => $title,
            'config' => $config,
            'schedule' => $schedule
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $offday = Offday::findOrFail($id);

        $this->validate($request, [
//            'custom_times' => 'required'
        ]);

//        return $request->all();


        $custom_times = $request->input('custom_night_times') ?? $request->input('custom_day_times');

        $data = [
            'enabled' => $request->input('enabled'),
            'times' => $request->input('custom_times'),
            'reason_for_change' => $request->input('reason_for_change'),
        ];

        $offday->update($data);

        $offday;

        session()->flash('flash_message', trans('admin.offday_updated_success_msg_with_date_and_shift', ['date' => '<strong>' . $offday->date . '</strong>', 'shift' => '<strong>' . trans('general.' . $offday->shift) . '</strong>']));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('ConfigController@reservationHours');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $offday = Offday::findOrFail($id);
        $offday->delete();

        return redirect()->back();
    }

}
