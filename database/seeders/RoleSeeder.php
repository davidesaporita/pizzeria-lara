<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Merchant', 'Customer'];

        foreach($roles as $key => $name) {
            $role = new Role();
            $role->id = $key+1;
            $role->name = $name;
            $role->save();
        }
    }
}
