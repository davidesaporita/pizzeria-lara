<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'category_id' => Category::all()->random()->id,
            'name' => 'Pizza ' . ucFirst($this->faker->word),
            'description' => $this->faker->realText(120),
            'price' => $this->faker->randomFloat(2, 5, 14),
            'image' => $this->faker->imageUrl(640, 480)
        ];
    }
}
