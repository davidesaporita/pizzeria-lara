<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function dashboard() 
    {
        $latestOrders = Order::orderBy('created_at', 'desc')->limit(5)->get();
        $totalOrders = Order::where('status', 'completed')->count();

        $data = [
            'products' => [
                'pizzas'     => Product::where('category_id', 1)->orderBy('created_at', 'desc')->get(), 
                'appetizers' => Product::where('category_id', 2)->orderBy('created_at', 'desc')->get(),
                'desserts'   => Product::where('category_id', 3)->orderBy('created_at', 'desc')->get()
            ],
            'orders' => $latestOrders,
            'totalOrders' => $totalOrders,
        ];

        return view('merchant.index', $data);
    }
}
