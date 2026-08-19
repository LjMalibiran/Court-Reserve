<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Court;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        $courts = [
            [
                'name' => 'Badminton Court 1', 
                'type' => 'Badminton', 
                'price_per_hour' => 150.00, 
                'is_active' => true
            ],
            [
                'name' => 'Badminton Court 2', 
                'type' => 'Badminton', 
                'price_per_hour' => 150.00, 
                'is_active' => true
            ],
            [
                'name' => 'Badminton Court 3', 
                'type' => 'Badminton', 
                'price_per_hour' => 200.00, // Maybe Court 3 is a premium or VIP court!
                'is_active' => true
            ],
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }
    }
}