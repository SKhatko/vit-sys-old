<?php namespace App\Http\Controllers;

use App\Tenant;
use App\Http\Requests;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Reservation;
use App\User;

use Mail;
use Artisan;

use Symfony\Component\Console\Output\BufferedOutput;

class TenantsController extends Controller
{

    private function setTenantDb($id)
    {
    
        $tenant = Tenant::findOrFail($id);

        DB::purge('tenant');
        
        Config::set('database.connections.tenant.host', $tenant->db_host);
        Config::set('database.connections.tenant.username', $tenant->db_username);
        Config::set('database.connections.tenant.password', $tenant->db_pass);
        Config::set('database.connections.tenant.database', $tenant->db_name);

        \App\Config::load();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $title = trans('manager.server_tenants');
        $pageName = 'tenants';

        $tenants = Tenant::notAdmin()->get();

        return view('manager.tenants.index')->with([
            'title' => $title,
            'tenants' => $tenants,
            'pageName' => $pageName
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $title = trans('manager.create_tenant');
        
        return view('manager.tenants.create')->with([
            'title' => $title
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
            'name' => 'required',
            'key_name' => 'required',
            'domain' => 'required',
            'subdomain' => 'required',
        ]);
        
        DB::transaction(function() use ($request) {
        
            $keyName = $request->input('key_name');
            $dbPassword = $this->generateRandomPassword();
            
            $name = $request->input('name');
            $domain = $request->input('domain');
            $subdomain = $request->input('subdomain');
            $host = Config::get('database.connections.tenant.host');
            
            $newDatabase = DB::statement("CREATE DATABASE ".$keyName." CHARACTER SET utf8 COLLATE utf8_general_ci");
            $newUser = DB::statement("CREATE USER '".$keyName."'@'%' IDENTIFIED BY '".$dbPassword."'");
            $addPrivileges = DB::statement("GRANT ALL PRIVILEGES ON ".$keyName.".* TO '".$keyName."'@'%'");

            $dateTime = date("Y-m-d H:i:s");
            
            $tenantId = DB::table('tenants')->insertGetId([
                'name' => $name,
                'domain' => $domain,
                'subdomain' => $subdomain,
                'db_host' => $host,
                'db_name' => $keyName,
                'db_username' => $keyName,
                'db_pass' => $dbPassword,
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
                'active' => true,
            ]);
            
            //run migrations
            Artisan::call('tenants:migrate');
            
            session()->flash('flash_message', 'Tenant created successfully');
            session()->flash('flash_message_type', 'alert-success');

            return redirect()->action('TenantsController@show', [$tenantId]);
        });
        
        return 'Error occurred! please contact server adminstrator';
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id, $tab = null)
    {
        $tenant = Tenant::findOrFail($id);
        $title = 'Tenant view - ' . $tenant->name;
        $pageName = 'manager';

        $reservationsData = [];
        $menuData = [];
        $users = [];
        $config = NULL;

        $currencies = [
            'EUR' => 'EUR',
            'USD' => 'USD'
        ];

        if (!$tab) {
            $tab = 'account';
        }
        else {
        
            $this->setTenantDb($id);
            
            if ($tab == 'pin') {
                
            } else if ($tab == 'reception-stats') {

                $reservationsData['monthly_stats'] = DB::connection('tenant')->table('reservations')
                    ->select(DB::raw('count(*) as COUNT, sum(persons_num) as PERSONS, DATE_FORMAT(date, "%Y-%m-01") as MONTH'))
                    ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m-01")'))
                    ->orderBy('date', 'desc')
                    ->get();


                $thisDate = date("Y-m-d", strtotime('-15 days'));
                $today = date("Y-m-d");
                $reservationsData['daily_stats'] = DB::connection('tenant')->table('reservations')
                    ->select(DB::raw('count(*) as COUNT, sum(persons_num) as PERSONS, date as DAY'))
                    ->where('date', '>=', $thisDate)
                    ->where('date', '<=', $today)
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();
            } else if ($tab == 'menu-stats') {

                $menuData['monthly_stats'] = DB::connection('tenant')->table('menu_visit_stats')
                    ->select(DB::raw('sum(views) as VIEWS, sum(visitors) as VISITORS, DATE_FORMAT(date, "%Y-%m-01") as MONTH'))
                    ->groupBy(DB::raw('DATE_FORMAT(date, "%Y-%m-01")'))
                    ->orderBy('date', 'desc')
                    ->get();


                $thisDate = date("Y-m-d", strtotime('-15 days'));
                $menuData['daily_stats'] = DB::connection('tenant')->table('menu_visit_stats')
                    ->where('date', '>=', $thisDate)
                    ->orderBy('date', 'desc')
                    ->get();

            } else if ($tab == 'users') {

                $users = User::all();
                
            } else if ($tab == 'config') {
            
                $config = new \App\Config();
            }
        }

        return view('manager.tenants.show')->with([
            'title' => $title,
            'tab' => $tab,
            'tenant' => $tenant,
            'pageName' => $pageName,

            'reservationsData' => $reservationsData,
            'menuData' => $menuData,
            'users' => $users,
            'config' => $config,
            'currencies' => $currencies,
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
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

    public function updateTenantConfig(Request $request, $id)
    {

        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        $this->validate($request, [
            'website' => 'url',
            'day_start' => 'required',
            'day_end' => 'required',
            'restaurant_name' => 'required',
            'currency' => 'required',
            //'timezone'	=>	'required'

            'address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'country' => 'required',
        ]);

        \App\Config::update($request->all());

        session()->flash('flash_message', 'Configuration updated successfully');
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->back();
    }

    public function createTenantUser($id) {
    
        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        $title = 'Create new tenant user (tenant: '.$tenant->name.')';

        return view('manager.tenants.create_user')->with([
            'title' => $title,
            'tenant' => $tenant
        ]);
    }
    
    public function storeTenantUser(Request $request, $id) {
        
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
        ]);
        
        $tenant = Tenant::findOrFail($id); // for $id validation
        $this->setTenantDb($id);
        
        $existingUser = User::where('email', 'LIKE', $request->input('email'))->first();
        if ($existingUser) {
            session()->flash('flash_message', 'User with same email already exists');
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }
        
        $password = $this->generateRandomPassword();
        $hashedPassword = bcrypt($password);
        
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = $hashedPassword;
        
        if ($user->save()) {
            session()->flash('flash_message', 'Account created successfully. Password: '.$password);
            session()->flash('flash_message_type', 'alert-success alert-important');

            return redirect()->action('TenantsController@show', [$id, 'users']);
        }
        else {
            session()->flash('flash_message', 'Error occurred while creating user account');
            session()->flash('flash_message_type', 'alert-danger');

            return redirect()->back();
        }   
    }
    
    public function editTenantUser($id, $userId)
    {

        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        $user = User::findOrFail($userId);

        $title = 'Edit Tenant User: ' . $tenant->name . ' / ' . $user->name;

        return view('manager.tenants.edit_user')->with([
            'title' => $title,
            'tenant' => $tenant,
            'user' => $user,
        ]);
    }

    public function updateTenantUser(UserRequest $request, $id, $userId)
    {

        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        $user = User::findOrFail($userId);

        $user->update($request->all());

        //set flash session success message
        session()->flash('flash_message', trans('manager.user_updated_successfully'));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->back();
    }
    
    public function destroyTenantUser($id, $userId = NULL) {
        
        if (!$userId) { //nullable only to allow pointing to action before attaching user id (in views)
            return null;
        }
        
        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);
        
        $user = User::findOrFail($userId);
        
        $user->delete();
        
        session()->flash('flash_message', trans('User deleted successfully'));
        session()->flash('flash_message_type', 'alert-danger');

        return redirect()->back();
    }

    public function resetUserPassword($id, $userId)
    {

        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        \App::setLocale(\App\Config::$language);

        $user = User::findOrFail($userId);

        //generate new password, and send it to the user's email
        $newPassword = $this->generateRandomPassword();
        $passwordHash = bcrypt($newPassword);

        $user->password = $passwordHash;
        $user->update();

        //send email to user
        $emailData = [
            'user' => $user,
            'newPassword' => $newPassword,
        ];

        $subject = "VITisch - " . trans('manager.new_password');

        //return view('emails.new_password_email')->with($emailData);

        Mail::send('emails.new_password_email', $emailData, function ($message) use ($emailData, $subject) {
            $message->to($emailData['user']->email, $emailData['user']->name)->subject($subject);
        });

        //set flash session success message
        session()->flash('flash_message', trans('manager.new_password_sent_successfully'));
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->back();
    }

    public function updateInterfaces(Request $request, $id)
    {

        $tenant = Tenant::findOrFail($id);

        $data = [
            'reception_enabled' => (boolean)$request->input('reception'),
            'kitchen_enabled'	=> (boolean)$request->input('kitchen'),
            'restaurant_enabled' => (boolean)$request->input('restaurant'),
            'clients_enabled' => (boolean)$request->input('clients'),
            'analytics_enabled' => (boolean)$request->input('analytics'),
            'admin_enabled' => (boolean)$request->input('admin'),
        ];

        $tenant->update($data);

        session()->flash('flash_message', 'Interfaces updated successfully');
        session()->flash('flash_message_type', 'alert-success');

        return redirect()->back();

    }

    public function migrateTenants()
    {

        header('Content-Type: text/plain', true);

        echo '<pre>';
        Artisan::call('tenants:migrate');

        echo '</pre>';

        return null;
    }

    private function generateRandomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    private function generatePin()
    {
        return rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
    }

    public function enablePin($id)
    {

        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        $randomPin = $this->generatePin();
        \App\Config::update(['pin' => $randomPin]);

        session()->flash('flash_message', 'Pin was enabled successfully. Pin code: <strong>' . $randomPin . '</strong>');
        session()->flash('flash_message_type', 'alert-success alert-important');

        return redirect()->back();
    }

    public function resetPin($id)
    {

        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        $randomPin = $this->generatePin();
        \App\Config::update(['pin' => $randomPin]);

        session()->flash('flash_message', 'Pin was reset successfully. Pin code: <strong>' . $randomPin . '</strong>');
        session()->flash('flash_message_type', 'alert-success alert-important');

        return redirect()->back();

    }

    public function disablePin($id)
    {

        $tenant = Tenant::findOrFail($id);
        $this->setTenantDb($id);

        \App\Config::update(['pin' => NULL]);

        session()->flash('flash_message', 'PIN code was disabled successfully');
        session()->flash('flash_message_type', 'alert-info alert-important');

        return redirect()->back();
    }
}
