<?php

namespace App\Console;

use App\Jobs\ProcessExpiredClaims;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Run every minute (or ->everyFiveMinutes())
        $schedule->job(new ProcessExpiredClaims)->everyMinute();
    }
}