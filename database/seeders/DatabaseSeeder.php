<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Added this to encrypt the password!

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creates the Master Admin Account automatically
        User::updateOrCreate(
            ['name' => 'Court Reserve'], // Look for this username...
            [
                'contact' => 'admin', // A placeholder contact so the DB doesn't complain
                'password' => Hash::make('123Court'), // Securely encrypts your password
                'role' => 'admin', // Grants master privileges
                'phone_verified_at' => now(), // Skips the OTP verification step
            ]
        );

        // Seed Courts
        if (\Illuminate\Support\Facades\DB::table('courts')->count() == 0) {
            \Illuminate\Support\Facades\DB::table('courts')->insert([
                ['id' => 1, 'is_active' => true],
                ['id' => 2, 'is_active' => true],
                ['id' => 3, 'is_active' => true],
            ]);
        }
    }
}