<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() 
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        $data = [
            'orders' => $orders
        ];

        return view('merchant.orders.index')->with($data);
    }
}
