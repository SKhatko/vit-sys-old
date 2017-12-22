<?php namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests;
use App\Http\Requests\CompanyRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CompaniesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('crm.companies');
        $pageName = 'companies';
        $companies = Company::with('reservationsCountRelation');

        $filterName = session()->get('filters.companies.name');

        if ($filterName) {
            $companies = $companies->where('name', 'LIKE', '%' . $filterName . '%');
        }

        $companies = $companies->paginate(20);

        return view('crm.companies.index')->with([
            'title' => $title,
            'pageName' => $pageName,
            'companies' => $companies,

            'filterName' => $filterName
        ]);
    }

    public function filter(Request $request)
    {

        if ($request->input('name')) {
            session()->put('filters.companies.name', $request->input('name'));
        } else {
            session()->forget('filters.companies.name');
        }

        return redirect()->action('CompaniesController@index');
    }

    public function clearFilters()
    {

        session()->forget('filters.companies.name');

        return redirect()->action('CompaniesController@index');
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
        $company = Company::findOrFail($id);
        $pageName = 'companies';
        $title = trans('crm.company_reservations_with_name', ['name' => $company->name]);
        $reservations = $company->reservations()->with(['client', 'status'])->get();

        return view('crm.companies.show')->with([
            'title' => $title,
            'company' => $company,
            'reservations' => $reservations,
            'pageName' => $pageName
        ]);
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
    public function update(CompanyRequest $request, $id)
    {
        $company = Company::findOrFail($id);

        //check if name already exists
        $name = $request->input('name');
        $exists = Company::where('name', '=', $name)->where('id', '!=', $id)->first();
        if ($exists) {

            // TODO style for flashed errors
            session()->flash('flash_message', trans('crm.duplicate_company_name_error_msg', ['name' => $name]));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }

        $company->update($request->all());

        return redirect()->back();
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

    /** Ajax Requests **/
    public function autocompleteName($string = NULL)
    {
        if (strlen($string) < 2) {
            return NULL;
        }

        $companies = Company::where('name', 'like', '%' . $string . '%')->get();
        $response = [
            'suggestions' => []
        ];

        foreach ($companies as $company) {

            $response['suggestions'][] = [
                'value' => $company->name,
                'data' => $company->id,
            ];
        }

        return $response;
    }
}
