<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $customers = User::where('role_id', '2')->get();

        return [
            'user_id' => $customers->random()->id,
            'status' => 'completed',
            'amount' => $this->faker->randomFloat(2, 14, 35)
        ];
    }
}
