<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $customers = $users->except(1);
        $merchant = $users->find(1);
        $products = Product::all();
        
        foreach($customers as $customer) {

            $open = 1;
            $faker = Faker::create();            
            $orders_per_customer = $faker->numberBetween(1, 4);

            if($customer->credit > 0) {                
                for($i = 0; $i < $orders_per_customer; $i++) {

                    $products_per_order = $faker->numberBetween(1, 3);

                    for($j = 0; $j < $products_per_order; $j++) {
                       
                        $product = $products->random();
                        $quantity = $faker->numberBetween(1, 2);                        
                        $subTotal = $product->price * $quantity;
    
                        if($subTotal < $customer->credit) {
                            if($open == 1) {
                                $order = new Order();
                                $order->user_id = $customer->id;
                                $order->amount = 0;
                                $order->status = 'in payment';
                                $open = 0;
                            }

                            // Prezzo del prodotto per la quantità
                            $order->amount += ($product->price * $quantity);

                            // Creare record nella tabella pivot
                            $data = ['quantity' => $quantity];

                            // Aggiungere all'array degli id prodotti quello corrente
                            $products_to_attach[] = $product->id;

                            // Scalare credito al cliente
                            $customer->credit -= $subTotal;
                            $customer->save();
                            
                            // Aggiungere credito al venditore
                            $merchant->credit += $subTotal;
                            $merchant->save();
                        }
                    }
                    if(isset($order)) {
                        $order->status = 'completed';
                        $order->save();
                        $open = 1;
                        foreach($products_to_attach as $product_id) {
                            $order->products()->attach($product->id, $data);
                        }
                    }
                }
            }
        }
    }
}
