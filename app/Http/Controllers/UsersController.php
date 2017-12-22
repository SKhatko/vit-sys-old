<?php namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = "Users";

        $users = User::all();

        return view('admin.users.index')->with([
            'title' => $title,
            'users' => $users
        ]);
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
    public function update(UserRequest $request, $id)
    {

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

    public function editPassword()
    {

        $title = trans('admin.change_password');
        $pageName = 'change-password';
        $user = Auth::user();

        return view('admin.users.change_password')->with([
            'title' => $title,
            'pageName' => $pageName,

            'user' => $user,
        ]);
    }

    public function updatePassword(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'new_password_confirmation' => 'required',
        ]);

        if (strcmp($request->input('new_password'), $request->input('new_password_confirmation'))) {

            session()->flash('flash_message', trans('admin.passwords_do_not_match_error_msg'));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }

        $user = Auth::user();

        $credentials = [
            'email' => $user->email,
            'password' => $request->input('current_password')
        ];

        if (!Auth::validate($credentials)) {

            session()->flash('flash_message', trans('admin.incorrect_current_password'));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }

        $password = $request->input('new_password');
        $passwordHash = bcrypt($password);

        $user->password = $passwordHash;
        $user->save();

        session()->flash('flash_message', trans('admin.password_updated_successfully'));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->back();
    }

}
