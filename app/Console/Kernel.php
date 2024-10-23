<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        \App\Console\Commands\SendReportCommand::class,
        \App\Console\Commands\SendTeaserCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    { #if there are more than 1 command (executed at the same time ): add ->runInBackground() for each one.
        $schedule->command('report:send');
        $schedule->command('teaser:send');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {

        // $this->load(__DIR__ . '/Commands');

        // require base_path('routes/console.php');
    }
}
