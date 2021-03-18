<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Console\Commands\SendPingcommand;

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
        $schedule->command('inspire')->everyMinute()
            ->appendOutputTo(storage_path('logs/inspire_schedule.log'));

        $schedule->command(SendPingcommand::class)
            ->daily()
            ->withoutOverlapping()
            ->onOneServer();
            //;
            
            //->evenInMaintenanceMode()
            //->runInBackground();
            //sendOutputTo
            //appendOutputTo
            //emailOutputTo
            //emailOutputOnFailure

            // sundays()->at('17:00')
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
