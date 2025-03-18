<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\OptionValue;

class ProductOptionSeeder extends Seeder
{
    public function run()
    {
        $productOptions = [
            'Couch' => [
                'Size' => ['Length up to 2m', 'Length up to 4m', 'Length more than 4m'],
            ],
            'Table' => [
                'Material' => ['Wood', 'Stone / Metal', 'Glass'],
                'Size' => ['2-4 persons', '6-8 persons', '9+ people'],
            ],
            'TV' => [
                'Size' => ['Up to 40 In (ca. 1m)', 'Up to 80 In (ca. 2m)', 'More than 80 In'],
            ],
            'Sideboard' => [
                'Size' => ['Up to 1.2m long', 'Up to 1.8m long', 'Up to 2.4m long'],
            ],
            'Shelf' => [
                'Type' => ['Dismantlable', 'Non dismantlable'],
                'Size' => [
                    '0.6m x 0.6m', '0.6m x 1.2m', '0.6m x 1.8m', 'More than 0.6m x 1.8m', '1.2m x 1.2m', '1.2m x 1.8m', 'More than 1.2m x 1.8m', '1.8m x 1.8m', 'More than 1.8m x 1.8m',
                ],
            ],
            'Plant' => [
                'Size' => ['Up to 1m', 'Up to 2m', 'More than 2m'],
                'Weight' => ['Up to 60kg', '60kg to 100kg', '100kg to 150kg', 'More than 150kg'],
            ],

            'Wardrobe' => [
                'Size' => ['1-2 doors', '3-4 doors', '5-6 doors', '7-8 doors'],
                'Rear wall' => ['nailed rear wall', 'non-nailed rear wall'],
                'Doors' => ['Normal doors', 'Sliding doors'],
            ],
            'Corner couch' => [
                'Size' => ['Length up to 2m', 'Length up to 4m', 'Length more than 4m'],
            ],
            'Drawer' => [
                'Size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
            ],

            'Picture' => [
                'Size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
            ],
            'Modern wall unit' => [
                'Size' => ['2 parts', '3 parts', '4 parts', '5 parts'],
            ],

            'Aquarium' => [
                'Size' => [
                    '60 x 30 x 30 | 54 L', '80 x 35 x 40 | 112 L', '100 x 40 x 40 | 160 L', '120 x 40 x 50 | 240 L', '150 x 50 x 60 | 450 L',
                ],
            ],

            'Kid\'s toy (big)' => [
                'Size' => ['60 x 60 x 60 cm', '1 x 1 x 1 m', '1.5 x 1.5 x 1.5 m'],
            ],

            'Trampoline' => [
                'Size' => ['Up to 1m diameter', 'Up to 2.5m diameter', 'More than 2.5m diameter'],
            ],

            'Bed' => [
                'Type' => [
                    'Single bed', 'Double bed', 'Couch bed', 'Loft bed', 'Boxspring bed', 'Waterbed', 'Folding bed', 'Canopy bed', 'Bunk bed',
                ],
            ],

            'Grand Piano' => [
                'Size' => ['Small', 'Big'],
            ],

            'Additional mattress' => [
                'Size' => ['Single', 'Double'],
            ],

            'Sculpture' => [
                'Size' => ['Up to 1m x 1m', 'Up to 2m x 2m', 'More than 2m x 2m'],
            ],
            'Wall clock' => [],
            'Loft bed' => [],
            'Long case clock' => [
                'Size' => ['Small', 'Big'],
            ],

            'Flower pot' => [
                'Content' => ['Empty', 'Full'],
            ],
            'Safe' => [
                'Weight' => ['Up to 100kg', 'Up to 200kg', 'More than 200kg'],
            ],
            'Baby crib' => [],
            'Waterbed' => [],
            'Weight bench & weights' => [
                'Weight' => ['Up to 100kg', 'Up to 200kg', 'Plus de 200kg'],
            ],

            'Freezer cabinet' => [
                'Size' => ['Small', 'Big'],
            ],

            'Deep-freezer' => [
                'Size' => ['Small', 'Big'],
            ],

            'Wardrobe with sliding doors' => [
                'Size' => ['1-2 sliding doors', '3-4 sliding doors', '5+ sliding doors'],
                'Nailed' => ['nailed rear wall', 'non-nailed rear wall'],
            ],

            'Country wardrobe' => [
                'Size' => ['1 door', '2 doors', '3 doors'],
            ],

            'Garden table' => [
                'Material' => ['Wood', 'Stone / Metal', 'Glass'],
            ],

            'Showcase' => [
                'Size' => ['1 door', '2 doors'],
            ],
            'Bureau' => [
                'Size' => ['1-parted', '2-parted'],
            ],

            'Classic wall unit' => [
                'Size' => [
                    'Up to 2m demontable', 'Up to 3m demountable', 'Up to 4m demountable', 'Up to 5m demountable',
                ],
            ],
            'Desk' => [
                'Size' => ['Length up to 2m', 'Length more than 2m'],
            ],

            'Mirror' => [
                'Size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
            ],
            'Grill' => [
                'Size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
                'Gas grill' => ['No', 'Yes'],
            ],
            'Deep-freezer' => [
                'Size' => ['Small', 'Big'],
            ],
        ];

        foreach ($productOptions as $productName => $options) {
            $product = Product::where('name', $productName)->first();
            if ($product) {
                foreach ($options as $optionName => $values) {
                    $option = ProductOption::create([
                        'product_id' => $product->id,
                        'name' => $optionName,
                    ]);

                    foreach ($values as $value) {
                        OptionValue::create([
                            'option_id' => $option->id,
                            'value' => $value,
                        ]);
                    }
                }
            }
        }
    }
}
