<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Redirect;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
	 protected $redirectTo = '/admin/dashboard';
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
             // User role
    $role = Auth::user()->user_role; 

    // Check user role
    switch ($role) {
        case 0:    // Super Admin
               return redirect()->route('dashboard');
            break;
        case 1:    // Admin
               return redirect()->route('categories');
            break; 
         case 2:      // Vendor
                return redirect()->route('vendor_home');
            break;
    }
        }

        return $next($request);
    }
	
	public function redirectTo(){
        
    // User role
    $role = Auth::user()->user_role; 
    // Check user role
	
    switch ($role) {
        case 0:    // Super Admin
               return redirect()->route('dashboard');
            break;
        case 1:    // Admin
               return redirect()->route('categories');
            break; 
         case 2:      // Vendor
                return redirect()->route('vendor_home');
            break;
    }
}
}