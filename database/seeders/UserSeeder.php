<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Venkat',
            'last_name' => '',
            'gender' => 'mr',
            'email' => 'venkat@example.com',
        ])->assignRole(RolesEnum::User->value);


        User::factory()->create([
            'name' => 'Vendor',
            'last_name' => '',
            'gender' => 'mr',
            'email' => 'vendor@example.com',
        ])->assignRole(RolesEnum::Vendor->value);

        User::factory()->create([
            'name' => 'Admin',
            'last_name' => '',
            'gender' => 'mr',
            'email' => 'admin@example.com',
        ])->assignRole(RolesEnum::Admin->value);
    }
}
