<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    
    
    public function toggle(Request $request)
    {
        // Get the current locale, default to 'en'
        $currentLocale = Session::get('locale', 'en');
    
        // Toggle locale
        $newLocale = $currentLocale === 'en' ? 'fa' : 'en';
        $newDirection = $newLocale === 'fa' ? 'rtl' : 'ltr';
    
        Log::info('Toggle language', [
            'current_locale' => $currentLocale,
            'new_locale' => $newLocale,
            'session_before' => Session::get('locale')
        ]);
    
        // Update session
        Session::put('locale', $newLocale);
        Session::put('direction', $newDirection);
        Session::save(); // Ensures persistence
    
        // Update application locale immediately
        App::setLocale($newLocale);
    
        Log::info('After toggle', [
            'app_locale' => App::getLocale(),
            'session_locale' => Session::get('locale')
        ]);
    
        // Store in a cookie
        return redirect()->back()->withCookie(cookie('locale', $newLocale, 60 * 24 * 365));
    }
    

    // public function toggle(Request $request)
    // {
    //     $currentLocale = Session::get('locale', 'en');
    //     $newLocale = $currentLocale === 'en' ? 'fa' : 'en';
    //     $newDirection = $newLocale === 'fa' ? 'rtl' : 'ltr';

    //     Log::info('Toggle language', [
    //         'current_locale' => $currentLocale,
    //         'new_locale' => $newLocale,
    //         'session_before' => Session::get('locale')
    //     ]);

    //     // Set session values
    //     Session::put('locale', $newLocale);
    //     Session::put('direction', $newDirection);
        
    //     // Set application locale
    //     app()->setLocale($newLocale);
        
    //     // Force save session
    //     Session::save();

    //     Log::info('After toggle', [
    //         'app_locale' => app()->getLocale(),
    //         'session_locale' => Session::get('locale')
    //     ]);

    //     $cookie = cookie('locale', $newLocale, 60*24*365);
        
    //     return redirect()->back()->withCookie($cookie);
    // }
    
    // public function toggle()
    // {
    //     $currentLocale = Session::get('locale', 'en');
    //     $newLocale = $currentLocale === 'en' ? 'fa' : 'en';
    //     $newDirection = $newLocale === 'fa' ? 'rtl' : 'ltr';

    //     Session::put('locale', $newLocale);
    //     Session::put('direction', $newDirection);
    //     app()->setLocale($newLocale); // Set the app locale
    //     session()->save(); // Force save the session
        
    //     // return redirect()->back();
    //     return redirect()->back()->withCookie(cookie('locale', $newLocale, 60*24*365));
    // }
}
