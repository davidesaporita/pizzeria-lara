<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class Merchant
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
            return $next($request);
        }

        // Customer
        if(Auth::user()->role_id == 2) {
            return redirect()->route('customer.index');
        }
    }
}
