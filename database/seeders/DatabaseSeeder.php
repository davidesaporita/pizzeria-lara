<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            UserSeeder::class
        ]);

        \App\Models\User::factory(10)->create();
        \App\Models\Product::factory(30)->create();

        $this->call([
            OrderSeeder::class,
        ]);        
    }
}
