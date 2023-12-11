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

        // $schedule->command('db:backup')->daily();
        // $schedule->command('backup:clean')->weekly();
        // $schedule->command('backup:run')->weekly();

        // Recurring invoice
        $schedule->command('recurring:run')->everyMinute();
        // Check subscription status
        $schedule->command('check:subs')->daily();
        // Delete 6 years old data from database
        $schedule->command('delete:old-data')->daily();
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
