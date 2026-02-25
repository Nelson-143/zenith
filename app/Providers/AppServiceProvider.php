<?php

namespace app\Providers;

use Illuminate\Http\Request;
use app\Breadcrumbs\Breadcrumbs;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event; // Use the correct Event facade
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;

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
        Paginator::useBootstrapFive();

        Request::macro('breadcrumbs', function () {
            return new Breadcrumbs($this);
        });

        // Track successful logins
        Event::listen(Login::class, function ($event) {
            $event->user->update([
                'last_login' => now(),
                'last_ip' => request()->ip(),
            ]);

            activity()
                ->causedBy($event->user)
                ->withProperties([
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Successful login');
        });

        // Track failed logins
        Event::listen(Failed::class, function ($event) {
            DB::table('failed_login_attempts')->insert([
                'email' => $event->credentials['email'],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);

            activity()
                ->withProperties([
                    'email' => $event->credentials['email'],
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Failed login attempt');
        });
    }
}
