<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Redirect;

class RedirectIfVendorAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
	 protected $redirectTo = '/home';
    public function handle($request, Closure $next, $guard = 'vendor')
    {
        if (Auth::guard($guard)->check()) {
         return redirect()->route('vendor_home');
        }

        return $next($request);
    }
	
	public function redirectTo(){
        
     return redirect()->route('vendor_home');
    }

}