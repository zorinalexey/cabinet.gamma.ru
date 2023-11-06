<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('update:base-passports')->daily();
        $schedule->command('check:users')->everyTwoHours();
        $schedule->command('update:base-rfm')->daily();
        $schedule->command('update:base-fromu')->daily();
        $schedule->command('update:base-mvk')->daily();
        $schedule->command('update:base-p639')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
