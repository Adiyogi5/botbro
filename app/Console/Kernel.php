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
        $schedule->command('update:ordercancel')->everyTenMinutes();
        $schedule->command('purchase:payment_status')->everyFiveMinutes();
        $schedule->command('update:userpurchasestatus')->everyMinute();
        $schedule->command('update:useragentstatus')->everyMinute();
        $schedule->command('update:badgereward')->everyTwoMinutes();
        $schedule->command('update:walletpayment')->everyTwoMinutes();

        $schedule->command('update:ledgerstatus')->monthlyOn(1, '00:00');
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
