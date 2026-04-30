<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@muwashi.com',
            'phone'    => '0300-0000000',
            'city'     => 'Lahore',
            'role'     => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Sample sellers
        $sellers = [];
        $cities  = ['Lahore', 'Karachi', 'Islamabad', 'Faisalabad', 'Multan', 'Peshawar'];
        foreach (range(1, 5) as $i) {
            $sellers[] = User::create([
                'name'     => "Seller $i",
                'email'    => "seller{$i}@muwashi.com",
                'phone'    => "0300-123456{$i}",
                'city'     => $cities[array_rand($cities)],
                'password' => Hash::make('password'),
            ]);
        }

        // Sample ads
        $animals = ['cow', 'goat', 'buffalo', 'bull', 'sheep', 'other'];
        $titles  = [
            'cow'     => 'Beautiful Sahiwal Cow for Sale',
            'goat'    => 'Beetal Goat — Ready for Qurbani',
            'buffalo' => 'Nili-Ravi Buffalo — High Milk Producer',
            'bull'    => 'Strong Desi Bull for Farming',
            'sheep'   => 'Healthy Dumba Sheep',
            'other'   => 'Mixed Livestock Package',
        ];

        foreach ($sellers as $sIndex => $seller) {
            foreach (array_slice($animals, 0, 3) as $aIndex => $animal) {
                $isFeatured = ($sIndex === 0 && $aIndex === 0);
                $isBoosted = ($sIndex === 1 && $aIndex === 1);

                Ad::create([
                    'user_id'     => $seller->id,
                    'title'       => $titles[$animal] . ' - ' . $seller->city,
                    'description' => "This is a healthy and well-fed {$animal} available for sale in {$seller->city}. Contact seller for more details.",
                    'price'       => rand(30000, 500000),
                    'animal_type' => $animal,
                    'breed'       => 'Desi',
                    'age_info'    => rand(1, 5) . ' years',
                    'city'        => $seller->city,
                    'area'        => 'Main Bazar',
                    'status'      => 'approved',
                    'expires_at'  => now()->addDays(30),
                    'is_featured' => $isFeatured,
                    'featured_expires_at' => $isFeatured ? now()->addDays(20) : null,
                    'is_boosted'  => $isBoosted,
                    'boost_expires_at' => $isBoosted ? now()->addDays(7) : null,
                ]);
            }
        }
    }
}
