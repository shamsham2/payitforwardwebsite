<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $DB_HOST = config('app.DB_HOST');
        $DB_DATABASE = config('app.DB_DATABASE');
        $DB_USERNAME = config('app.DB_USERNAME');
        $DB_PASSWORD = config('app.DB_PASSWORD');
        $datetime = date("M_d_Y_H_i_s");

        // backup the database to ec2 and to s3 bucket
        $schedule->exec('/usr/bin/mysqldump --host='.$DB_HOST.' --user='.$DB_USERNAME.' --password='.$DB_PASSWORD.'  '.$DB_DATABASE.'  -r  /home/fearnley/pif_database_backup/'.$datetime.'.sql')->hourly();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
