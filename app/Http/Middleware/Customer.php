<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        // Merchant
        if(Auth::user()->role_id == 1) {
            return redirect()->route('merchant.index');
        }

        // Customer
        if(Auth::user()->role_id == 2) {
            return $next($request);
        }
    }
}
