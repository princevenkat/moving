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
                'size' => ['Length up to 2m', 'Length up to 4m', 'Length more than 4m'],
            ],
            'Table' => [
                'material' => ['Wood', 'Stone / Metal', 'Glass'],
                'size' => ['2-4 persons', '6-8 persons', '9+ people'],
            ],
            'TV' => [
                'size' => ['Up to 40 In (ca. 1m)', 'Up to 80 In (ca. 2m)', 'More than 80 In'],
            ],
            'Sideboard' => [
                'size' => ['Up to 1.2m long', 'Up to 1.8m long', 'Up to 2.4m long'],
            ],
            'Shelf' => [
                'type' => ['Dismantlable', 'Non dismantlable'],
                'size' => [
                    '0.6m x 0.6m', '0.6m x 1.2m', '0.6m x 1.8m', 'More than 0.6m x 1.8m', '1.2m x 1.2m', '1.2m x 1.8m', 'More than 1.2m x 1.8m', '1.8m x 1.8m', 'More than 1.8m x 1.8m',
                ],
            ],
            'Plant' => [
                'size' => ['Up to 1m', 'Up to 2m', 'More than 2m'],
                'weight' => ['Up to 60kg', '60kg to 100kg', '100kg to 150kg', 'More than 150kg'],
            ],

            'Wardrobe' => [
                'size' => ['1-2 doors', '3-4 doors', '5-6 doors', '7-8 doors'],
                'rear-wall' => ['nailed rear wall', 'non-nailed rear wall'],
                'Doors' => ['Normal doors', 'Sliding doors'],
            ],
            'Corner couch' => [
                'size' => ['Length up to 2m', 'Length up to 4m', 'Length more than 4m'],
            ],
            'Drawer' => [
                'size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
            ],

            'Picture' => [
                'size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
            ],
            'Modern wall unit' => [
                'size' => ['2 parts', '3 parts', '4 parts', '5 parts'],
            ],

            'Aquarium' => [
                'size' => [
                    '60 x 30 x 30 | 54 L', '80 x 35 x 40 | 112 L', '100 x 40 x 40 | 160 L', '120 x 40 x 50 | 240 L', '150 x 50 x 60 | 450 L',
                ],
            ],

            'Kid\'s toy (big)' => [
                'size' => ['60 x 60 x 60 cm', '1 x 1 x 1 m', '1.5 x 1.5 x 1.5 m'],
            ],

            'Trampoline' => [
                'size' => ['Up to 1m diameter', 'Up to 2.5m diameter', 'More than 2.5m diameter'],
            ],

            'Bed' => [
                'type' => [
                    'Single bed', 'Double bed', 'Couch bed', 'Loft bed', 'Boxspring bed', 'Waterbed', 'Folding bed', 'Canopy bed', 'Bunk bed',
                ],
            ],

            'Grand Piano' => [
                'size' => ['Small', 'Big'],
            ],

            'Additional mattress' => [
                'size' => ['Single', 'Double'],
            ],

            'Sculpture' => [
                'size' => ['Up to 1m x 1m', 'Up to 2m x 2m', 'More than 2m x 2m'],
            ],
            'Wall clock' => [],
            'Loft bed' => [],
            'Long case clock' => [
                'size' => ['Small', 'Big'],
            ],

            'Flower pot' => [
                'content' => ['Empty', 'Full'],
            ],
            'Safe' => [
                'weight' => ['Up to 100kg', 'Up to 200kg', 'More than 200kg'],
            ],
            'Baby crib' => [],
            'Waterbed' => [],
            'Weight bench & weights' => [
                'weight' => ['Up to 100kg', 'Up to 200kg', 'Plus de 200kg'],
            ],

            'Freezer cabinet' => [
                'size' => ['Small', 'Big'],
            ],

            'Deep-freezer' => [
                'size' => ['Small', 'Big'],
            ],

            'Wardrobe with sliding doors' => [
                'size' => ['1-2 sliding doors', '3-4 sliding doors', '5+ sliding doors'],
                'nailed' => ['nailed rear wall', 'non-nailed rear wall'],
            ],

            'Country wardrobe' => [
                'size' => ['1 door', '2 doors', '3 doors'],
            ],

            'Garden table' => [
                'material' => ['Wood', 'Stone / Metal', 'Glass'],
            ],

            'Showcase' => [
                'size' => ['1 door', '2 doors'],
            ],
            'Bureau' => [
                'size' => ['1-parted', '2-parted'],
            ],

            'Classic wall unit' => [
                'size' => [
                    'Up to 2m demontable', 'Up to 3m demountable', 'Up to 4m demountable', 'Up to 5m demountable',
                ],
            ],
            'Desk' => [
                'size' => ['Length up to 2m', 'Length more than 2m'],
            ],

            'Mirror' => [
                'size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
            ],
            'Grill' => [
                'size' => ['Length up to 1m', 'Length up to 2m', 'Length more than 2m'],
                'gas-grill' => ['No', 'Yes'],
            ],
            'Deep-freezer' => [
                'size' => ['Small', 'Big'],
            ],
        ];

//        foreach ($productOptions as $productName => $options) {
//            $product = Product::where('name', $productName)->first();
//
//            if (!$product) {
//                $this->command->warn("Product '$productName' not found.");
//                continue;
//            }
//
//            foreach ($options as $optionName => $values) {
//                $option = ProductOption::create([
//                    'product_id' => $product->id,
//                    'name' => $optionName,
//                ]);
//
//                foreach ($values as $value) {
//                    OptionValue::create([
//                        'option_id' => $option->id,
//                        'value' => $value,
//                    ]);
//                }
//            }
//        }
//
//        $this->command->info('Product options and values seeded successfully.');

        foreach ($productOptions as $productName => $options) {
        $product = Product::where('name', $productName)->first();

        if (!$product) {
            $this->command->warn("Product '$productName' not found. Skipping...");
            continue;
        }

        $optionNames = [];
        $optionValues = [];

        foreach ($options as $optionName => $values) {
            $optionNames[] = $optionName; // unprefixed
            $optionValues['option_' . $optionName] = $values; // prefixed
        }

        $product->options = $optionNames;
        $product->option_values = $optionValues;
        $product->save();

        $this->command->info("Updated product: $productName");
    }

        $this->command->info('Product options and values assigned to products table.');
    }
}
