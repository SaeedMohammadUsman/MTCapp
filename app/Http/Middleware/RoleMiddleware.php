<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Exceptions\UnauthorizedException;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
     
     public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $user = Auth::user();

        if (!$user) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role) 
            ? $role
            : explode('|', $role);

        // if (!$user->hasAnyRole($roles)) {
        //     throw UnauthorizedException::forRoles($roles);
        // }

        return $next($request);
    }
    // public function handle(Request $request, Closure $next, $role)
    // {
        
    //     // Check if the user has the required rolec
    //     if (!Auth::user()->hasRole($role)) {
    //         // If not, redirect or abort with a 403 Unauthorized status
    //         abort(403, 'Unauthorized');
    //     }

    //     return $next($request);
    // }
}
