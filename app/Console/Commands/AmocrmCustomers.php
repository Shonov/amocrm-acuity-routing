<?php

namespace App\Console\Commands;

use App\Helpers\AmoHelper;
use Illuminate\Console\Command;
use App\Contracts\Facades\ChannelLog as Logger;

class AmocrmCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amo-customers:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        if (env('STOP_AMOCRM_CUSTOMERS_CRON')) {
            return;
        }
        Logger::channel('amo');
        Logger::info('[Amo] Started Amo customers cron');

        $helper = new AmoHelper();
        $helper->runCron();
    }
}
