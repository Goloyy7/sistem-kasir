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
        for ($i = 1; $i <= 20; $i++) {
            User::updateOrCreate(
                ['email' => "user{$i}@example.com"], // biar gak dobel kalau seed ulang
                [
                    'name'         => fake()->name(),
                    'password'     => Hash::make('password123'), // password sama semua
                    'phone_number' => '08' . fake()->numerify('##########'),
                    'address'      => fake()->address(),
                    'is_active'    => true,
                ]
            );
        }
    }
}
