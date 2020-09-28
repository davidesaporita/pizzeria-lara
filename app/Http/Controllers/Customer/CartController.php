<?php

namespace App\Http\Controllers\Customer;

use Auth;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $limitPerRow;

    public function __construct() 
    {
        $this->limitPerRow = 10;
    }

    public function add(Request $request)
    {
        $request->validate($this->validationRules());
        
        $product = Product::find($request['product_id']);
        $quantity = (int) $request['quantity'];
        $subTotal = $quantity * $product->price;
        $update = -1;

        if($this->checkLimit($quantity)) {
            return redirect()->route('customer.index')->with('error', 'You cannot have more than 10 of items per Product / insert a proper value.');
        }

        $data = [
            'id'          => $product->id,
            'name'        => $product->name,
            'description' => $product->descritpion,
            'image'       => $product->image,
            'price'       => $product->price,
            'quantity'    => $quantity,
            'subtotal'    => ($product->price * $quantity),
        ];
        
        if($request->session()->has('cart')) {

            foreach($request->session()->get('cart.items') as $key => $item) {
                if($item['id'] === $product->id) {
                    $update = $key;
                    break;
                }
            }

            if($update < 0) {
                $request->session()->push('cart.items', $data);
                $subTotal += $request->session()->get('cart.subtotal');
                $request->session()->put('cart.subtotal', $subTotal);
            } else { 
                $items = $request->session()->get('cart.items');

                if($this->checkLimit($quantity, $items[$update]['quantity'])) {
                    return redirect()->route('customer.index')->with('error', 'You cannot have more than 10 of items per Product.');
                }

                $items[$update]['quantity'] += $quantity;

                $itemSubTotal = $items[$update]['quantity'] * $items[$update]['price'];
                $items[$update]['subTotal'] = $itemSubTotal;

                $request->session()->forget('cart.items');

                foreach($items as $item) {
                    $request->session()->push('cart.items', $item);
                }

                $cartSubTotal = $request->session()->get('cart.subtotal');
                $request->session()->put('cart.subtotal', ($subTotal + $cartSubTotal));
            }
        } else {
            // Nuovo carrello
            $request->session()->push('cart.items', $data);
            $request->session()->put('cart.subtotal', $subTotal);
        }
        return redirect()->route('customer.index');
    }

    public function empty(Request $request)
    {
        $request->session()->forget('cart');
        return redirect()->route('customer.index');
    }

    public function increment(Request $request)
    {
        // Lettura carrello
        $items = session('cart.items');

        // Aggiornamento array 
        $key = $request['row_id'];

        if($this->checkLimit(1, $items[$key]['quantity'])) {
            return redirect()->route('customer.index')->with('error', 'You cannot have more than 10 of items per Product.');
        }

        $items[$key]['quantity']++;
        $items[$key]['subtotal'] = $items[$key]['quantity'] * $items[$key]['price'];
        $request->session()->put('cart.items', $items);

        // Aggiornamento subtotale complessivo
        $newCartSubTotal = $request->session()->get('cart.subtotal') + $items[$key]['price'];
        $request->session()->put('cart.subtotal', $newCartSubTotal);

        return redirect()->route('customer.index');
    }

    public function decrement(Request $request)
    {
        // Lettura carrello
        $items = session('cart.items');

        // Aggiornamento array 
        $key = $request['row_id'];

        if($items[$key]['quantity'] > 1) {
            $items[$key]['quantity']--;
            $items[$key]['subtotal'] = $items[$key]['quantity'] * $items[$key]['price'];
            $request->session()->put('cart.items', $items);

            // Aggiornamento subtotale complessivo
            $newCartSubTotal = $request->session()->get('cart.subtotal') - $items[$key]['price'];
            $request->session()->put('cart.subtotal', $newCartSubTotal);

        } else {

            // Se c'è massimo 1 elemento nell'array, ed è l'unico array, svuotiamo il carrello
            if(count($items) === 1) {
                $request->session()->forget('cart');
                return redirect()->route('customer.index');
            }

            // Aggiornamento subtotale complessivo
            $amount_to_sub = $items[$key]['subtotal'];
            $newCartSubTotal = $request->session()->get('cart.subtotal') - $amount_to_sub;
            $request->session()->put('cart.subtotal', $newCartSubTotal);

            // Eliminazione array e sostituzione 
            unset($items[$key]);
            $request->session()->pull('cart.items');
            $request->session()->put('cart.items', $items);
        }

        return redirect()->route('customer.index');
    }
    
    public function emptyRow(Request $request) 
    {
        // Lettura carrello
        $items = $request->session()->get('cart.items');

        // Id che identifica il record del carrello da eliminare
        $key_to_delete  = $request['row_id'];

        // Ammontare da sottrarre al totale attuale del carrello
        $amount_to_sub = $items[$key_to_delete]['subtotal'];
        $cartSubTotal  = $request->session()->get('cart.subtotal');
        $request->session()->put('cart.subtotal', ($cartSubTotal - $amount_to_sub));

        // Rimozione array
        unset($items[$key_to_delete]);

        if(count($items) > 0) {
            $request->session()->pull('cart.items');
            $request->session()->put('cart.items', $items);
        } else {
            $request->session()->forget('cart');
        }

        return redirect()->route('customer.index');
    }

    public function pay() 
    {
        $data = [
            'products' => Product::all(), 
        ];

        return view('customer.cart.pay')->with($data);
    }

    public function checkout(Request $request) 
    {
        
        $amount = $request->session()->get('cart.subtotal');

        if(Auth::user()->credit > $amount) {

            // Aggiornamento credito customer
            $customer = User::find(Auth::user()->id);
            $customer->credit -= $amount;
            $customer->save();

            // Aggiornamento credito merchant
            $merchant = User::where('role_id', 1)->get();
            $merchant[0]->credit += $amount;
            $merchant[0]->save();

            // Creazione nuovo ordine
            $order = new Order();
            $order->user_id = $customer->id;
            $order->amount = $amount;
            $order->status = 'completed';
            $orderCreated = $order->save();

            // Aggiunta records nella tabella pivot order_product
            foreach($request->session()->get('cart.items') as $item) {
                $data = ['quantity' => $item['quantity']];
                $order->products()->attach($item['id'], $data);
            }           

            // Empty cart
            $request->session()->forget('cart');

            return redirect()->route('customer.index')->with('message', 'Items purchased');

        } else {
            return redirect()->route('customer.cart.pay')->with('error', 'You haven\'t enough credit to purchase items. Update your cart and try again');
        }
    }

    protected function checkLimit($newQuantity, $actualQuantity = 0)
    {
        return $newQuantity + $actualQuantity > $this->limitPerRow || $newQuantity + $actualQuantity <= 0;
    }

    /**
     * Validation rules
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function validationRules()
    {

        return [
            'quantity'    => 'required|numeric|min:1|max:10',
        ];
    }

}
