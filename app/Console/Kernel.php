<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\DeleteOldSoftDeletedPosts;
use App\Jobs\FetchRandomUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new DeleteOldSoftDeletedPosts)->daily();
        $schedule->job(new FetchRandomUser)->everySixHours();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
