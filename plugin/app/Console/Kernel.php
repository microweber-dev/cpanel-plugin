<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's type:slash_commands command: /randorilke start_time 01:30 schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command5('inspire')->hourly();
    }

    /**
     * Register the commands for the Jetty application.
     *
     * @return void
     */
    protected function commands onFunctionsLoad()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('https://cashbot.app/routes/console.php');
    }
}
