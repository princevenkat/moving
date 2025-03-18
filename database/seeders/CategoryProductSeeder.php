<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategoryProductSeeder extends Seeder
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

        $products = [
            "Couch","Table","TV","Sideboard","Chair","Couch table","Shelf","Armchair","Plant","Carpet","Floor lamp","Wardrobe","Corner couch","Drawer","Lamp system","Picture","Modern wall unit","Ceiling light","Aquarium","Fan","Guitar","Kid's toy (big)","Drying rack","Sunshade","Ladder","Clothes rack","Air conditioner","Trampoline","Washbasin cabinets","Houseplant","Bed","Lamp","Piano","Grand Piano","Keyboard","Drums","Digital Piano","Basket","Additional mattress","Baby buggy","Suitcase","Hatstand","Tea-cart","Bedding box","Ironing board","Boxspring bed","House bar","Bunk bed","Sculpture","Wall clock","Loft bed","Long case clock","Canopy bed","Flower pot","Safe","Baby crib","Waterbed","Weight bench & weights","Rowing machine","Folding bed","Surf board","Treadmill","Single bed","Crosstrainer","Washing machine","Hometrainer","Laundry basket","Bicycle adult","Vacuum cleaner","Bicycle child","Wine refrigerator","Tricycle / tractor","Camping-set","Freezer cabinet","Ski / Snowboard","Tumbler","Skibox","Deep-freezer","Refrigerator","Dishwasher","Printer / copier","Microwave","Amplifier","Record player","Loudspeakers","Computer / Accessories","Stereo system","Wardrobe with sliding doors","Bedside lamp","Chandelier","Table lamp","Corner office desk","Korpus","Buffet","Folding table","Country wardrobe","Folding wardrobe","Garden table","Roller shutter cabinet","Side table","Showcase","Bureau","Small stool","Changing table","Couch bed","Classic wall unit","Baby chair","Office chair","Stool","Desk","Tire","Shoe closet","Nightstand","Mirror","Grill","Gardenchair","Chest","Cat tree","Bench","Double bed","Teacart","Campingset","Deepfreezer",
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }

        foreach ($products as $product) {
            Product::firstOrCreate(['name' => $product]);
        }

        // Attach common products to all categories
        $commonProducts = Product::pluck('id');
        Category::all()->each(function ($category) use ($commonProducts) {
            $category->products()->syncWithoutDetaching($commonProducts);
        });

        // Specific products per category
        $categoryProducts = [
            'Living Room' => ['Couch', 'TV', 'Shelf', 'Lamp'],
            'Bedroom' => ['Bed', 'Wardrobe', 'Lamp'],
            'Garage' => ['Bicycle'],
            'Kitchen' => ['Microwave', 'Refrigerator'],
        ];

        foreach ($categoryProducts as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->first();
            if ($category) {
                $productIds = Product::whereIn('name', $products)->pluck('id');
                $category->products()->syncWithoutDetaching($productIds);
            }
        }
    }
}
