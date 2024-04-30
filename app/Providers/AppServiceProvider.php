<?php

namespace App\Providers;

use App\Models\Attendee;
use App\Models\Event;
use App\Policies\EventPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate::define('update-event', function($user, Event $event){
        //     return $user->id === $event->user_id;
        // });
        // Gate::define('delete-attendee', function($user, Attendee $attendee, Event $event){
        //     return $user->id === $attendee->user_id || $user->id === $event->user_id;
        // });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

    }
}
