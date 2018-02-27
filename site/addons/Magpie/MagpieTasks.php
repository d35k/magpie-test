<?php

namespace Statamic\Addons\Magpie;

use Statamic\Extend\Tasks;
use Illuminate\Console\Scheduling\Schedule;

class MagpieTasks extends Tasks
{
    /**
     * Define the task schedule
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    public function schedule(Schedule $schedule)
    {
        $schedule->command('magpie:backup --no-create')->everyMinute();
    }
}
