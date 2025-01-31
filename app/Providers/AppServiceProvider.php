<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Notifications\ResetPassword;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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
        $locale = Session::get('locale', 'en'); // Default to English
        App::setLocale($locale);
        Schema::defaultStringLength(191);
        
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return url(route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]));
        });
    }
}
