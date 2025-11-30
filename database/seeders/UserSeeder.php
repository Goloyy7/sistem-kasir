<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name'         => fake()->name(),
                    'password'     => Hash::make('password'),
                    'phone_number' => '08' . fake()->numerify('##########'),
                    'address'      => fake()->address(),
                    'is_active'    => true,
                ]
            );
        }

        User::create([
            'name' => 'Indra',
            'email' => 'kasir@sistem.com',
            'password' => Hash::make('password'),
            'phone_number' => '087844417321',
            'address' => 'Jl. Sukapadang No 271',
            'is_active' => true,
        ]);
    }
}
