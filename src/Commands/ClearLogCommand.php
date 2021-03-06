<?php

namespace Sedehi\Payment\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;
use DB;
use Config;

class ClearLogCommand extends Command
{

    /**
     * The console command name.
     * @var string
     */
    protected $name = 'payment:clear-logs';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Deleting logs of unsuccessful payments since a specified date';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function fire()
    {
        $dateTime = $this->argument('date');
        if(is_null($dateTime) || strtolower($dateTime) == 'now'){
            $dateTime = Carbon::now();
        }else{
            $dateTime = Carbon::createFromFormat('Y-m-d', $dateTime)->second(0)->minute(0)->hour(0);
        }
        $delete = DB::table(Config::get('payment::table').'_log')->where('created_at', '<', $dateTime)->delete();
        $this->info($delete.' records has been deleted from '.$dateTime.' and before');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'date',
                InputArgument::REQUIRED,
                'Now, For present date; or date as y-m-d',
            ],
        ];
    }

}
