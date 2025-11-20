<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '081234567890',
            'address' => 'Jl. Contoh Alamat No.123, Kota Contoh',
            'is_active' => true,
        ]);

        $this->call(AdminSeeder::class);
        $this->call(UserSeeder::class);
    }
}
