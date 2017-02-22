<?php

namespace App\Listeners;

use App\Events\PeriodWasOpened;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PeriodWasOpenedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PeriodWasClosed  $event
     * @return void
     */
    public function handle(PeriodWasOpened $event)
    {
        $event->period->clearStatistics();
    }
}
