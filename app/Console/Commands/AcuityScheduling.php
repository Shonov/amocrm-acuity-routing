<?php

namespace App\Console\Commands;

use App\Http\Controllers\AcuityController;
use Illuminate\Console\Command;
use App\Contracts\Facades\ChannelLog as Logger;

class AcuityScheduling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acuity:start';

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
        if (env('STOP_ACUITY_CRON')) {
            return;
        }
        Logger::channel('acuity');
        Logger::info('[Cron] Started Acuity cron');

        $acuity = new AcuityController();
        $acuity->runCron();
    }
}
