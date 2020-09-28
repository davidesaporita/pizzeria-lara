<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->isMerchant()) {
            return redirect()->route('merchant.index');
        }

        if(Auth::user()->isCustomer()) {
            return redirect()->route('customer.index');
        }        

        return view('home');
    }
}
