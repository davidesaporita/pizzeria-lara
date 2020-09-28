<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $categories = ['Pizza', 'Appetizer', 'Dessert'];
        
        $category = new Category();
        $category->id = 1;
        $category->name = $categories[0];
        $category->save();

        $category = new Category();
        $category->id = 2;
        $category->name = $categories[1];
        $category->save();

        $category = new Category();
        $category->id = 3;
        $category->name = $categories[2];
        $category->save();
    }
}
