<?php

namespace App\Listeners;

use App\Events\PeriodWasClosed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PeriodWasClosedListener
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
    public function handle(PeriodWasClosed $event)
    {
        $event->period->calculateStatistics();
    }
}
