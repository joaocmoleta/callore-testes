<?php

namespace App\Console;

use App\Jobs\Inverno2024Job;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
        $schedule->call(function () {
            $delivery = Delivery::select('id')
            ->where('notification', 1)->first();
            
            if($delivery) {
                Log::info(static::class . ' linha ' . __LINE__ . ' Consultando se há alterações no status da encomenda.');
                Artisan::call('tracking:total');
            }
        // })->everyMinute();
        })->everyTwoHours();

        // $schedule->command('sent:mail-mkt')->dailyAt('7:30');
        // $schedule->command('inspire')->hourly();
        // $schedule->command('tracking:total')->everyFiveMinutes();

        // $schedule->command('tracking:total')->everyMinute();

        // $schedule->command('tracking:total')->dailyAt('6:00');
        // $schedule->command('tracking:total')->dailyAt('12:00');
        // $schedule->command('tracking:total')->dailyAt('18:00');
        // $schedule->command('tracking:total')->dailyAt('23:00');
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
