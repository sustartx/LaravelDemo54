<?php

namespace App\Listeners;

use App\Events\Operation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartBackupListener
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
     * @param  Operation  $event
     * @return void
     */
    public function handle(Operation $event)
    {
        if(in_array('StartBackup', $event->operations)){
            \Log::info("Start backup cron job...");
        }
    }
}
