<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Living Room',
            'Bedroom',
            'Dining Room',
            'Kitchen',
            'Bathroom & Hallway',
            'Office',
            'Attic',
            'Basement',
            'Garage',
            'Garden / Balcony',
            'Nursery',
            'Storeroom'
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category]);
        }
    }
}
