<?php namespace App\Http\Controllers;

use App\Client;
use App\ClientStatus;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DB;

class ClientsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('crm.clients');
        $pageName = 'clients';
        $clients = Client::with(['status', 'reservationsCountRelation']);

        $statuses = ClientStatus::pluck('name', 'id')->all();

        foreach ($statuses as $key => $value) {
            $statuses[$key] = trans('crm.' . $value);
        }

        $filterName = session()->get('filters.clients.name');
        $filterPhone = session()->get('filters.clients.phone');
        $filterEmail = session()->get('filters.clients.email');
        $filterStatusId = session()->get('filters.clients.status_id');

        if ($filterName) {
            $clients = $clients->where(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', '%' . $filterName . '%');
        }
        if ($filterPhone) {
            $clients = $clients->where(function ($query) use ($filterPhone) {
                $query->where('phone', 'LIKE', '%' . $filterPhone . '%')->orWhere('mobile', 'LIKE', '%' . $filterPhone . '%');
            });
        }
        if ($filterEmail) {
            $clients = $clients->where('email', 'LIKE', '%' . $filterEmail . '%');
        }
        if ($filterStatusId) {
            $clients = $clients->where('status_id', '=', $filterStatusId);
        }

        $clients = $clients->paginate(25);

        $genders = [
            'male' => trans('general.male'),
            'female' => trans('general.female')
        ];

        return view('crm.clients.index')->with([
            'title' => $title,
            'pageName' => $pageName,
            'clients' => $clients,
            'statuses' => $statuses,
            'genders' => $genders,
            'filterName' => $filterName,
            'filterPhone' => $filterPhone,
            'filterEmail' => $filterEmail,
            'filterStatusId' => $filterStatusId
        ]);
    }

    public function filter(Request $request)
    {

        if ($request->input('name')) {
            session()->put('filters.clients.name', $request->input('name'));
        } else {
            session()->forget('filters.clients.name');
        }

        if ($request->input('phone')) {
            session()->put('filters.clients.phone', $request->input('phone'));
        } else {
            session()->forget('filters.clients.phone');
        }

        if ($request->input('email')) {
            session()->put('filters.clients.email', $request->input('email'));
        } else {
            session()->forget('filters.clients.email');
        }

        if ($request->input('status_id')) {
            session()->put('filters.clients.status_id', $request->input('status_id'));
        } else {
            session()->forget('filters.clients.status_id');
        }

        return redirect()->action('ClientsController@index');
    }

    public function clearFilters()
    {
        session()->forget('filters.clients.name');
        session()->forget('filters.clients.phone');
        session()->forget('filters.clients.email');
        session()->forget('filters.clients.status_id');

        return redirect()->action('ClientsController@index');
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
    public function store(Request $request)
    {
        $this->validate($request, [
            'last_name' => 'required',
            'first_name' => 'required',
        ]);

        Client::create($request->all());

        return redirect()->action('ClientsController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $pageName = 'clients';
        $client = Client::findOrFail($id);
        $title = trans('crm.client_profile_with_name', ['name' => $client->name]);
        $reservations = $client->reservations()->with('status')->get();

        $statuses = ClientStatus::pluck('name', 'id')->all();

        foreach ($statuses as $key => $value) {
            $statuses[$key] = trans('crm.' . $value);
        }

        $genders = [
            'male' => trans('general.male'),
            'female' => trans('general.female')
        ];

        return view('crm.clients.show')->with([
            'title' => $title,
            'pageName' => $pageName,
            'client' => $client,
            'statuses' => $statuses,
            'genders' => $genders,
            'reservations' => $reservations,
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
        $client = Client::findOrFail($id);

        $title = trans('crm.client_profile_with_name', ['name' => $client->name]);

        $statuses = ClientStatus::pluck('name', 'id')->all();

        foreach ($statuses as $key => $value) {
            $statuses[$key] = trans('crm.' . $value);
        }

        $genders = [
            'male' => trans('general.male'),
            'female' => trans('general.female')
        ];

        return view('crm.clients.edit')->with([
            'title' => $title,
            'client' => $client,
            'statuses' => $statuses,
            'genders' => $genders
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
        $this->validate($request, [
            'last_name' => 'required',
        ]);

        $client = Client::findOrFail($id);

        $client->update($request->all());

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
    public function autocompleteLastName($string = NULL)
    {
        if (strlen($string) < 2) {
            return NULL;
        }

        $clients = Client::with('status')->where('last_name', 'like', '%' . $string . '%')
            ->where(function ($query) {
                $query->where('mobile', '!=', '')->orWhere('phone', '!=', '');
            })->get();

        $response = [
            'suggestions' => []
        ];

        foreach ($clients as $client) {

            $response['suggestions'][] = [
                'value' => $client->last_name,
                'data' => $client->id,
                'extra' => [
                    'first_name' => $client->first_name,
                    'phone' => $client->phone,
                    'mobile' => $client->mobile,
                    'reservations' => $client->active_reservations_count,
                    'status_id' => $client->status_id,
                    'status_name' => $client->status->name,
                    'email' => $client->email,
                    'gender' => $client->gender,
                    'sticky' => $client->sticky_note,
                ]
            ];
        }

        return $response;
    }

    public function autocompleteName($string = NULL)
    {
        if (strlen($string) < 2) {
            return NULL;
        }

        $clients = Client::where('name', 'like', '%' . $string . '%')->get();
        $response = [
            'suggestions' => []
        ];

        foreach ($clients as $client) {

            $response['suggestions'][] = [
                'value' => $client->name,
                'data' => $client->id,
                'extra' => [
                    'phone' => $client->phone,
                    'reservations' => $client->active_reservations_count,
                    'status_id' => $client->status_id,
                    'mobile' => $client->mobile,
                    'email' => $client->email,
                    'gender' => $client->gender,
                    'sticky' => $client->sticky_note
                ]
            ];
        }

        return $response;
    }

    public function autocompletePhone($string = NULL)
    {

        if (strlen($string) < 3) {
            return NULL;
        }

        $clients = Client::with('status')->where('phone', 'like', '%' . $string . '%')->get();

        $response = [
            'suggestions' => []
        ];

        foreach ($clients as $client) {
            $response['suggestions'][] = [
                'value' => $client->phone,
                'data' => $client->id,
                'extra' => [
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'reservations' => $client->active_reservations_count,
                    'status_id' => $client->status_id,
                    'status_name' => $client->status->name,
                    'mobile' => $client->mobile,
                    'email' => $client->email,
                    'gender' => $client->gender,
                    'sticky' => $client->sticky_note
                ]
            ];
        }

        return $response;
    }

    public function autocompleteMobile($string = NULL)
    {
        if (strlen($string) < 3) {
            return NULL;
        }

        $clients = Client::with('status')->where('mobile', 'like', '%' . $string . '%')->get();
        $response = [
            'suggestions' => []
        ];

        foreach ($clients as $client) {

            $response['suggestions'][] = [
                'value' => $client->mobile,
                'data' => $client->id,
                'extra' => [
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'reservations' => $client->active_reservations_count,
                    'status_id' => $client->status_id,
                    'status_name' => $client->status->name,
                    'phone' => $client->phone,
                    'email' => $client->email,
                    'gender' => $client->gender,
                    'sticky' => $client->sticky_note
                ]
            ];
        }

        return $response;
    }

}
