<?php namespace App\Http\Controllers;

use App\SectionMapObject;
use App\Table;
use App\Section;
use App\Reservation;
use App\Http\Requests;
use App\Http\Requests\SectionRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SectionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('restaurant.restaurant_sections');
        $pageName = 'sections';
        $sections = Section::ordered()->get();

        return view('restaurant.sections.index')->with([
            'title' => $title,
            'pageName' => $pageName,
            'sections' => $sections
        ]);
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
    public function store(SectionRequest $request)
    {
        $section = Section::create($request->all());

        session()->flash('flash_message', trans('restaurant.store_section_success_msg', ['section_name' => $section->name]));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('SectionsController@index');
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(SectionRequest $request, $id)
    {
        $section = Section::findOrFail($id);

        $section->update($request->all());

//        session()->flash('flash_message', trans('restaurant.section_update_success_msg', ['section_name' => $section->name]));
//        session()->flash('flash_message_type', 'alert-success');

        return redirect()->action('SectionsController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $section = Section::findOrFail($id);

        SectionMapObject::sectionId($id)->delete();
        $section->delete();

        return redirect()->action('SectionsController@index');
    }

    public function order(Request $request)
    {
        $sections = $request->input('data');

        foreach ($sections as $sectionOrder => $sectionId) {
            $section = Section::findOrFail($sectionId);
            $section->update(['order_num' => $sectionOrder]);
        }
    }
}
