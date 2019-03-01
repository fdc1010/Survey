<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
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
 		Event::listen('Illuminate\Auth\Events\Login', function ($event) {						
            $user = $event->user;
            $user->api_token = str_random(60);
			$user->is_online = 1;
			$user->save();
		});
		Event::listen('Illuminate\Auth\Events\Logout', function ($event) {						
            $user = $event->user;
			try{
				$user->api_token = null;
				$user->is_online = 0;
				$user->save();
			}catch(\Exception $e){
				
			}
		});
    }
}
