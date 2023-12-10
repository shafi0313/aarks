<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->call(function () {
        //     info('MANOAR '. now());
        // })->everyMinute();

        // $schedule->command('inspire')->hourly();

        // $schedule->command('db:backup')->everyMinute();
        //  ->daily();
        $schedule->command('recurring:run')->everyMinute();
        // $schedule->command('recurring:run')->daily();
        $schedule->command('check:subs')->daily();

        $schedule->command('delete:old-data')->daily();
        // $schedule->command('backup:clean')->weekly();
        // $schedule->command('backup:run')->weekly();
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
