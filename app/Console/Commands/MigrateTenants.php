<?php namespace App\Console\Commands;

use App\Tenant;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\BufferedOutput;

use Config;
use Artisan;
use DB;

class MigrateTenants extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tenants:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the migrations for each of the application tenants.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        define("LINE_BREAK", "\r\n");

        $this->info('migrating general database');

        $output = new BufferedOutput;

        $result = Artisan::call('migrate', [
            '--path' => 'database/migrations'
        ], $output);

        $result = Artisan::output();
        $this->info($result);
        echo $result . LINE_BREAK;


        $this->info('initiating tenants migration process');
        echo "initiating tenants migration process" . LINE_BREAK . LINE_BREAK;

        $tenants = Tenant::active()->get();

        foreach ($tenants as $tenant) {

            //reset tenant connection (using DB::purge instead of DB::disconnect) to delete the connection cache as well
            DB::purge('tenant');

            Config::set('database.connections.tenant.host', $tenant->db_host);
            Config::set('database.connections.tenant.username', $tenant->db_username);
            Config::set('database.connections.tenant.password', $tenant->db_pass);
            Config::set('database.connections.tenant.database', $tenant->db_name);

            if ($tenant->admin) {
                $this->info('Migrating admin database: ' . $tenant->db_name);
                echo "Migrating admin database: " . $tenant->db_name . LINE_BREAK;

                $output = new BufferedOutput;

                $result = Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => 'database/admin-migrations',
                    '--force' => true
                ], $output);

                $result = Artisan::output();
                $this->info($result);
                echo $result . LINE_BREAK;
            } else {
                $this->info('Migrating database: ' . $tenant->db_name);
                echo "Migrating database: " . $tenant->db_name . LINE_BREAK;

                $output = new BufferedOutput;

                $result = Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => 'database/tenant-migrations',
                    '--force' => true
                ], $output);

                $result = Artisan::output();
                $this->info($result);
                echo $result . LINE_BREAK;
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
        /*
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
        */
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
        /*
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
        */
    }

}
