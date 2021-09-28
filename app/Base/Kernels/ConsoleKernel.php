<?php

namespace App\Base\Kernels;

use Illuminate\Console\Scheduling\Schedule;
use App\Abstractions\Kernels\ConsoleKernel as AbstractConsoleKernel;

class ConsoleKernel extends AbstractConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected array $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule( Schedule $schedule ): void
    {
        // $schedule->command('inspire')->hourly();
    }
}
