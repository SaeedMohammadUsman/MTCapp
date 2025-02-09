<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle($request, Closure $next)
    // {
    //     $locale = Session::get('locale', config('app.locale'));
    //     Log::info('Middleware: Before setting locale', [
    //         'app_locale' => App::getLocale(),
    //         'session_locale' => $locale
    //     ]);
    
    //     App::setLocale($locale);
    //     Log::info('Middleware: After setting locale', [
    //         'app_locale' => App::getLocale()
    //     ]);


    //     return $next($request);
    // }
    
    
    public function handle($request, Closure $next)
{
    $locale = session('locale', config('app.locale')); // Use session() helper for consistency

    // Log::info('Middleware: Before setting locale', [
    //     'app_locale' => App::getLocale(),
    //     'session_locale' => $locale
    // ]);

    App::setLocale($locale);

    // Log::info('Middleware: After setting locale', [
    //     'app_locale' => App::getLocale()
    // ]);

    return $next($request);
}


}
