<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Defining unique merchant
        $merchant = new User();
        $merchant->first_name = 'Jeff';
        $merchant->last_name = 'Bezos';
        $merchant->role_id = 1; // Merchant
        $merchant->email = 'merchant@test.com';
        $merchant->email_verified_at = now();
        $merchant->password = Hash::make('password');
        $merchant->credit = 40;
        $merchant->remember_token = Str::random(10);
        $merchant->save();
    }
}
