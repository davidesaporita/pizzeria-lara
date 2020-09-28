<?php

namespace App\Http\Controllers\Merchant;

use Auth;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'categories' => Category::all()
        ];

        return view('merchant.products.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules());
        
        $data = $request->all();
        
        $data['user_id'] = Auth::id();
        if(isset($data['image'])) {
            $path = $data['image']->store('images', 's3');
            $data['image'] = Storage::disk('s3')->url($path);
        }
        
        $product = new Product();
        $product->fill($data);
        $saved = $product->save();

        if($saved) {
            return redirect()->route('merchant.index')->with('message', 'New item created');
        } else {
            return redirect()->route('merchant.products.create')->with('message', 'Error creating new product');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        
        $itemsPurchased = 0;
        
        foreach($product->orders as $order) {   
            $itemsPurchased += $order->pivot->quantity;
        }
        
        $data = [
            'product' => $product,
            'itemsPurchased' => $itemsPurchased
        ];

        return view('merchant.products.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {

        $data = [
            'product' => $product,
            'categories' => Category::all()
        ];

        return view('merchant.products.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate($this->validationRules());

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if(!empty($data['image'])) {

            // Controlla se c'è un url già presente per questa immagine su s3
            $url_exploded = explode('images/', $product->image);

            if(is_array($url_exploded) && count($url_exploded) > 1) {
                $path = 'images/' . $url_exploded[1];

                if(Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
            }
            
            $path = $data['image']->store('images', 's3');
            $data['image'] = Storage::disk('s3')->url($path);
        }

        $updated = $product->update($data);

        if($updated) {
            if(session('toShow')) die('toShow');
            return redirect()->route('merchant.products.show', $product->id)->with('message', "Item $product->name updated");
        } else {
            return redirect()->route('merchant.products.edit')->with('message', "Error updating $product->name");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(empty($product)) {
            abort(404);
        }

        $deleted_product = $product->name;

        // Delete image
        Storage::disk('public')->delete($product->image);
        $deleted = $product->delete();

        if($deleted) {
            return redirect()->route('merchant.index')->with('message', "$deleted_product was deleted");
        } else {
            return redirect()->route('merchant.index')->with('message', "Error deleting product $deleted_product");
        }
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
            'name'        => 'required|string|min:4|max:100',
            'category_id' => 'required|numeric|exists:categories,id',
            'description' => 'required|string|min:4|max:255',
            'price'       => 'required|numeric|min:1|max:100',
            'image'       => 'file|image|mimes:jpeg,jpg,png|max:2048',
        ];
    }
}
