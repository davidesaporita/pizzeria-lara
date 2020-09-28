<?php

namespace App\Http\Controllers\Customer;

use Auth;

use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard() 
    {
        $data = [
            'products' => [
                'pizzas'     => Product::where('category_id', 1)->orderBy('created_at', 'desc')->get(), 
                'appetizers' => Product::where('category_id', 2)->orderBy('created_at', 'desc')->get(),
                'desserts'   => Product::where('category_id', 3)->orderBy('created_at', 'desc')->get()
            ],
            'orders' => Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->limit(5)->get()
        ];

        return view('customer.index')->with($data);;
    }
}
