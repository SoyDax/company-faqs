<?php

namespace Database\Seeders;

use App\Models\User; // Import the User clas
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; // Import the Hash class


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([

            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => '$2y$12$Ycs4.QxaXGi26/kwJivO1OdZWF73znsp9lsT/wQkO0Es/JzGL5SWe',
            'remember_token' => Str::random(10),

        ])->assignRole('admin');
    }
}
