<?php namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Session;

class PinController extends Controller
{
    public function getLogin()
    {
        $requestedUrl = Session::get('auth.pin.requested_url');

        return view('auth.pin')->with([
            'requestedUrl' => $requestedUrl,
            'title' => trans('auth.pin_authorization')
        ]);

    }

    public function postLogin(Request $request)
    {

        $pin = $request->input('pin');

        $incorrectPin = false;
        if (!$pin || strlen($pin) != 4) {
            $incorrectPin = true;
        } else {
            $configPin = \App\Config::$pin;
            if (strcmp($pin, $configPin)) {
                $incorrectPin = true;
            }
        }

        if ($incorrectPin) {
            session()->flash('flash_message', trans('auth.incorrect_pin_inserted'));
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        } else {
            $requestedUrl = $requestedUrl = Session::get('auth.pin.requested_url');
            Session::put('auth.pin.auth_flag', time());

            return redirect($requestedUrl);
        }
    }
}
