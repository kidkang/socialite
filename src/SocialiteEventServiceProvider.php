<?php

namespace Yjtec\Socialite;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class SocialiteEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Yjtec\Socialite\Events\SocialiteLogUser' => [
            'Yjtec\Socialite\Listeners\SocialiteLogUserListener'
        ]
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
