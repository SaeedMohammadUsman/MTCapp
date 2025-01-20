<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Split roles if multiple roles are provided (e.g., role1|role2)
        $roles = is_array($role) ? $role : explode('|', $role);

        // Use the hasRole method to check if the user has any of the specified roles
        if (!$user->hasRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
    }
}




// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Exceptions\UnauthorizedException;
// // use Spatie\Permission\Traits\HasRoles;
// use Spatie\Permission\Traits\HasRoles;

// class RoleMiddleware
// {
    
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure  $next
//      * @param  string  $role
//      * @return mixed
//      */
//     public function handle(Request $request, Closure $next, $role)
//     {
//         if (!Auth::check()) {
//             throw UnauthorizedException::notLoggedIn();
//         }

//         $user = Auth::user();
        
//         // Split roles if multiple roles are provided (role1|role2)
//         $roles = is_array($role) ? $role : explode('|', $role);

//         // Check if the user has any of the required roles
//         if (!$user->hasAnyRole($roles)) {
//             throw UnauthorizedException::forRoles($roles);
//         }

//         return $next($request);
//     }
// }
