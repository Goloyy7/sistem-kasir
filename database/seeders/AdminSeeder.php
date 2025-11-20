<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name'     => 'Admin Satu',
                'email'    => 'admin1@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Dua',
                'email'    => 'admin2@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Tiga',
                'email'    => 'admin3@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Empat',
                'email'    => 'admin4@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Lima',
                'email'    => 'admin5@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Enam',
                'email'    => 'admin6@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Tujuh',
                'email'    => 'admin7@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Delapan',
                'email'    => 'admin8@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Sembilan',
                'email'    => 'admin9@example.com',
                'password' => 'password123',
            ],
            [
                'name'     => 'Admin Sepuluh',
                'email'    => 'admin10@example.com',
                'password' => 'password123',
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name'     => $admin['name'],
                    'password' => Hash::make($admin['password']),
                ]
            );
        }
    }
}
