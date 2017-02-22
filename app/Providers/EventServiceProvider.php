<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PeriodWasClosed' => [
            'App\Listeners\PeriodWasClosedListener',
        ],
        'App\Events\PeriodWasOpened' => [
            'App\Listeners\PeriodWasOpenedListener',
        ],
        'App\Events\Operation' => [
            'App\Listeners\SendMailListener',
            'App\Listeners\StartBackupListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
