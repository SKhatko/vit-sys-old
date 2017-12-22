<?php

namespace App\Http\Controllers;

use App\TablePlanRecord;
use App\TablePlanSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TablePlan;
use Carbon\Carbon;
use App\Section;
use App\SectionMapObject;
use DB;

class TablePlansController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('restaurant.table_plans');
        $pageName = 'table-plans';
        $tablePlans = TablePlan::ordered()->get();
        $sections = Section::all();
        // return $tablePlans;
        return view('restaurant.table_plans.index')->with([
            'title' => $title,
            'pageName' => $pageName,
            'tablePlans' => $tablePlans,
            'sections' => $sections
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('restaurant.create_table_plan');
        $pageName = 'table_plans';
        $sections = Section::ordered()->pluck('name', 'id')->all();
        $tablePlans = TablePlan::pluck('name', 'id')->all();

        return view('restaurant.table_plans.create')->with([
            'title' => $title,
            'pageName' => $pageName,
            'sections' => $sections,
            'tablePlans' => $tablePlans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'table_plan_create' => 'required'
        ]);

        $tablePlan = TablePlan::create([
            'name' => $request->input('table_plan_create')
        ]);

        $tablePlanId = $tablePlan->id;

        $loadObjectsFromTablePlanId = $request->input('table_plan_create_from');
        if ($loadObjectsFromTablePlanId) {
            $tablePlanFrom = TablePlan::findOrFail($loadObjectsFromTablePlanId);

            $sectionObjects = $tablePlanFrom->section_map_objects;
            foreach ($sectionObjects as $sectionObject) {
                $newSectionObject = $sectionObject;
                unset($newSectionObject->id);
                $newSectionObject->table_plan_id = $tablePlanId;

                SectionMapObject::insert($sectionObject->toArray());
            }
        }
        session()->flash('flash_message', trans('restaurant.table_plan_created_msg'));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('TablePlansController@show', $tablePlanId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pageName = 'table-plans';
        $sections = Section::ordered()->pluck('name', 'id')->all();
        $tablePlans = TablePlan::pluck('name', 'id')->all();
        $tablePlan = TablePlan::findOrFail($id);
        $title = $tablePlan->name;

        $tablePlanObjects = SectionMapObject::tablePlan($id)->get()->groupBy('section_id');

        return view('restaurant.table_plans.show')->with([
            'title' => $title,
            'pageName' => $pageName,
            'sections' => $sections,
            'tablePlans' => $tablePlans,
            'tablePlan' => $tablePlan,
            'tablePlanObjects' => $tablePlanObjects
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->input('table_plan_create')) {
            TablePlan::findOrFail($id)->update([
                'name' => $request->input('table_plan_create')
            ]);
        } else if ($request->has('table_plan_objects')) {
            $tablePlanObjects = json_decode($request->input('table_plan_objects'), true);
            $requestObjects = [];

            if ($tablePlanObjects) {
                foreach ($tablePlanObjects as $sectionId => $sectionObjects) {
                    if ($sectionObjects) {
                        foreach ($sectionObjects as $sectionObject) {
                            unset($sectionObject['id']);
                            $requestObjects[] = $sectionObject;
                        }
                    }

                    DB::beginTransaction();
                    try {
                        SectionMapObject::tablePlan($id)->delete();
                        SectionMapObject::insert($requestObjects);
                    } catch (ValidationException $e) {
                        DB::rollBack();
                        throw $e;
                    }
                    DB::commit();
                }
            } else {
                SectionMapObject::tablePlan($id)->delete();
            }
        }

        session()->flash('flash_message', trans('restaurant.table_plan_updated_msg'));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('TablePlansController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tablePlan = TablePlan::findOrFail($id);
        $tablePlanName = $tablePlan->name;
        TablePlanRecord::tablePlan($id)->delete();
        SectionMapObject::tablePlan($id)->delete();
        TablePlanSchedule::getInstance()->tablePlanDeleted($id);

        $tablePlan->delete();

        session()->flash('flash_message', trans('restaurant.table_plan_deleted_msg', ['section_name' => $tablePlanName]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('TablePlansController@index');
    }

    public function tablePlanSchedule()
    {
        $title = trans('restaurant.table_plan_schedule');
        $pageName = 'table-plan-schedule';
        $tablePlanSchedule = TablePlanSchedule::getInstance();
        $dailyTablePlanSchedule = $tablePlanSchedule->getDailySchedule();
        $defaultTablePlanId = $tablePlanSchedule->default_table_plan_id;
        $tablePlans = TablePlan::pluck('name', 'id')->all();
        $tablePlanRecords = TablePlanRecord::from(date('Y-m-d'))->orderBy('date')->get();
        $sections = Section::all();
        $shifts = [
            'day' => trans('general.day'),
            'night' => trans('general.night')
        ];
        $date = date("Y-m-d");

        return view('restaurant.table_plans.schedule')->with([
            'title' => $title,
            'pageName' => $pageName,
            'defaultTablePlanId' => $defaultTablePlanId,
            'dailyTablePlanSchedule' => $dailyTablePlanSchedule,
            'tablePlans' => $tablePlans,
            'tablePlanRecords' => $tablePlanRecords,
            'sections' => $sections,
            'shifts' => $shifts,
            'date' => $date
        ]);
    }

    public function updateTablePlanSchedule(Request $request)
    {
        if ($request->has('default_table_plan_id')) {
            TablePlanSchedule::getInstance()->setDailySchedule($request->input('default_table_plan_id'));
        } else {
            TablePlanSchedule::getInstance()->updateSchedule($request->except('_token'));
        }

        return redirect()->action('TablePlansController@tablePlanSchedule');
    }

    public function removeTablePlanRecord($id)
    {
        TablePlanRecord::destroy($id);
 
        return redirect()->action('TablePlansController@tablePlanSchedule');
    }

    public function createTablePlanRecord(Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'table_plan_id' => 'required',
            'shift' => 'required'
        ]);

        $existing = TablePlanRecord::date($request->input('date'))->shift($request->input('shift'))->first();
        if ($existing) {
            $existing->delete();
        }

        TablePlanRecord::create($request->all());

        return redirect()->action('TablePlansController@tablePlanSchedule');
    }

    public function order(Request $request)
    {
        $plans = $request->input('data');

        foreach ($plans as $planOrder => $id) {
            $plan = TablePlan::findOrFail($id);
            $plan->update(['order_num' => $planOrder]);
        }
    }
}
