<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Cow', 'slug' => 'cow', 'image_icon' => '🐄'],
            ['name' => 'Goat', 'slug' => 'goat', 'image_icon' => '🐐'],
            ['name' => 'Camel', 'slug' => 'camel', 'image_icon' => '🐪'],
            ['name' => 'Sheep', 'slug' => 'sheep', 'image_icon' => '🐑'],
            ['name' => 'Buffalo', 'slug' => 'buffalo', 'image_icon' => '🐃'],
            ['name' => 'Bull', 'slug' => 'bull', 'image_icon' => '🐂'],
            ['name' => 'Other', 'slug' => 'other', 'image_icon' => '🐾'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }
    }
}
