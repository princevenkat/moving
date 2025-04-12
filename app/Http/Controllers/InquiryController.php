<?php

//namespace App\Http\Controllers;
//
//use App\Models\Inquiry;
//use Illuminate\Http\Request;
//use Inertia\Inertia;
//
//class InquiryController extends Controller
//{
//    public function create()
//    {
//        return Inertia::render('Inquiry/Step1');
//    }
//
//    public function store(Request $request)
//    {
//        $request->validate([
//            'service' => 'required|in:moving,cleaning,moving-handover-cleaning',
//            'current_country' => 'required|string|max:255',
//            'current_zip' => 'required|string|max:10',
//            'current_city' => 'required|string|max:255',
//            'destination_country' => 'nullable|string|max:255',
//            'destination_zip' => 'nullable|string|max:10',
//            'destination_city' => 'nullable|string|max:255',
//            'email' => 'required|email|max:255'
//        ]);
//
//        $inquiry = Inquiry::create($request->all());
//
//        return redirect()->route('inquiry.step2', ['id' => $inquiry->id]);
//    }
//
//
//    public function currentHome($id)
//    {
//        $inquiry = Inquiry::findOrFail($id);
//        return Inertia::render('Inquiry/CurrentHome', ['inquiry' => $inquiry]);
//    }
//
//
//    public function updateCurrentHome(Request $request, $id)
//    {
//        $inquiry = Inquiry::findOrFail($id);
//
//        $request->validate([
//            'current_home_type' => 'required|in:house,apartment,shared_flat,storage,office',
//            'floor' => 'required|string|max:255',
//            'rooms' => 'required|integer|min:1',
//            'square_meters' => 'required|integer|min:1',
//            'accessibility_long_distance' => 'boolean',
//            'accessibility_distance_meters' => 'nullable|integer|min:1',
//            'accessibility_has_steps' => 'boolean',
//            'accessibility_steps' => 'nullable|integer|min:1',
//            'accessibility_impeded' => 'boolean',
//            'accessibility_notes' => 'nullable|string'
//        ]);
//
//        $inquiry->update($request->all());
//
//        return redirect()->route('inquiry.edit_new_home', ['id' => $inquiry->id]);
//    }
//
//
//    public function editNewHome($id)    {
//        $inquiry = Inquiry::findOrFail($id);
//        return Inertia::render('Inquiry/NewHome', ['inquiry' => $inquiry]);
//        //return Inertia::render('Inquiry/NewHome', [ 'inquiry' => $inquiry   ]);
//    }
//
//    public function updateNewHome(Request $request, $id)
//    {
//        $inquiry = Inquiry::findOrFail($id);
//
//        $validatedData = $request->validate([
//            'new_home_type' => 'required|string',
//            'floor' => 'nullable|string',
//            'easy_access' => 'nullable|string',
//            'accessibility_long_distance' => 'boolean',
//            'accessibility_distance_meters' => 'nullable|string',
//            'accessibility_has_steps' => 'boolean',
//            'accessibility_steps' => 'nullable|string',
//            'accessibility_impeded' => 'boolean',
//            'accessibility_notes' => 'nullable|string',
//            'people' => 'nullable|string',
//            'boxes' => 'nullable|string',
//            'additional_services' => 'array',
//        ]);
//
//        $inquiry->update($validatedData);
//
//        return redirect()->route('inquiry.edit_new_home', ['id' => $inquiry->id])
//            ->with('success', 'New home details updated successfully.');
//    }
//
//}




namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryItems;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
//    public function create()
//    {
//        return Inertia::render('Inquiry/Step1');
//    }
    public function create(Request $request)
    {
        $inquiryId = $request->query('inquiry');
        $inquiry = $inquiryId ? Inquiry::find($inquiryId) : null;

        return Inertia::render('Inquiry/Step1', [
            'inquiry' => $inquiry,
            'auth' => [
                'user' => auth()->user(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|string',
            'current_country' => 'required|string',
            'current_zip' => 'required|string',
            'current_city' => 'required|string',
            'destination_country' => 'nullable|string',
            'destination_zip' => 'nullable|string',
            'destination_city' => 'nullable|string',
            'email' => 'required|email',
        ]);

        $user = auth()->user();

        // If no user is logged in, check or create based on email
        if (!$user) {
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => 'Guest User',
                    'password' => bcrypt(uniqid()), // secure temp password
                ]
            );
            auth()->login($user);
        }

        $validated['user_id'] = $user->id;

        $inquiryId = $request->input('inquiry_id');
        $inquiry = $inquiryId ? Inquiry::find($inquiryId) : null;

        if ($inquiry) {
            $inquiry->update($validated);
        } else {
            $inquiry = Inquiry::create($validated);
        }

        return redirect()->route('inquiry.step2', ['inquiry' => $inquiry->id]);
    }


//    public function store(Request $request)
//    {
//        $validated = $request->validate([
//            'service_type' => 'required',
//            'current_country' => 'required',
//            'current_zip' => 'required',
//            'current_city' => 'required',
//            'destination_country' => 'nullable',
//            'destination_zip' => 'nullable',
//            'destination_city' => 'nullable',
//            'email' => 'required|email',
//        ]);
//
//        $user = auth()->user();
//
//        if (!$user) {
//            // Check if a user already exists with this email
//            $user = User::where('email', $request->email)->first();
//
//            if (!$user) {
//                // Create a guest user
//                $user = User::create([
//                    'name' => 'Guest User',
//                    'email' => $request->email,
//                    'password' => bcrypt(uniqid()), // Temporary password
//                ]);
//            }
//        }
//
//        // Add the user_id to validated data
//        $validated['user_id'] = $user->id;
//
//        // Create the inquiry associated with the user
//        $inquiry = Inquiry::create($validated);
//
//        // Redirect to next step
//        return redirect()->route('inquiry.step2', ['inquiry' => $inquiry->id]);
//        //return Inertia::location(route('inquiry.step2', ['inquiry' => $inquiry->id]));
//
//    }


    //    STEP 2
    public function step2(Inquiry $inquiry)
    {
        return Inertia::render('Inquiry/Step2', ['inquiry' => $inquiry]);
    }

    public function step2Store(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'current_home_type' => 'nullable|in:House,Apartment,Shared Flat,Storage,Office', // ✅ Only valid types
            'floor' => 'nullable|string',
            'rooms' => 'nullable|numeric',
            'square_meters' => 'nullable|integer',
            'has_elevator' => 'nullable|string',
            'distance_meters' => 'nullable|integer',
            'num_steps' => 'nullable|integer',
            'impeded_details' => 'nullable|string',
        ]);

        // Ensure valid values for has_elevator
        $validated['has_elevator'] = in_array(strtolower($validated['has_elevator']), ['yes', 'no']) ? $validated['has_elevator'] : 'no';

        $inquiry->update($validated);

        ///dd($validated);
        return redirect()->route('inquiry.step3', ['inquiry' => $inquiry->id]);
        //return Inertia::location(route('inquiry.step3', ['inquiry' => $inquiry->id]));

    }


    //    STEP 3
    public function step3(Inquiry $inquiry)
    {
        return Inertia::render('Inquiry/Step3', ['inquiry' => $inquiry]);
    }
    public function step3Store(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'new_home_type' => 'nullable|in:House,Apartment,Shared Flat,Storage,Office', // ✅ Only valid types
            'new_home_floor' => 'nullable|string',
            'new_home_rooms' => 'nullable|numeric',
            'new_home_square_meters' => 'nullable|integer',
            'new_home_has_elevator' => 'nullable|string',
            'new_home_distance_meters' => 'nullable|integer',
            'new_home_num_steps' => 'nullable|integer',
            'new_home_impeded_details' => 'nullable|string',
        ]);

        // Ensure valid values for has_elevator
        $validated['new_home_has_elevator'] = in_array(strtolower($validated['new_home_has_elevator']), ['yes', 'no']) ? $validated['has_elevator'] : 'no';

        $inquiry->update($validated);


        return redirect()->route('inquiry.step4', ['inquiry' => $inquiry->id]);
        //return Inertia::location(route('inquiry.step4', ['inquiry' => $inquiry->id]));
    }


//    public function step4(Inquiry $inquiry)
//    {
//        return Inertia::render('Inquiry/Step4', [
//            'inquiry' => $inquiry,
//            'categories' => Inventory::all(),
//            'inventoryItems' => InventoryItems::all()
//        ]);
//    }


    //    STEP 4
    public function step4(Inquiry $inquiry)
    {
        $categories = \App\Models\Category::with('products')->get();
        $inventoryItems = [];

        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                // Decode options (if needed)
                $options = $product->getAttribute('options') ?? [];
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }

                // Decode option_values and flatten to correct format
                $rawOptionValues = $product->getAttribute('option_values') ?? [];
                if (is_string($rawOptionValues)) {
                    $rawOptionValues = json_decode($rawOptionValues, true);
                }

                // Handle format: [{ "option_name": [...] }]
                $flattenedOptionValues = [];
                if (is_array($rawOptionValues)) {
                    foreach ($rawOptionValues as $item) {
                        if (is_array($item)) {
                            foreach ($item as $key => $value) {
                                $flattenedOptionValues[$key] = $value;
                            }
                        }
                    }
                }

                $inventoryItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'inventory_id' => $category->id,
                    'options' => $options,
                    'option_values' => $flattenedOptionValues,
                    'image' => $product->image
                        ? asset('storage/' . $product->image)
                        : null,
                ];

//                $imagePath = $product->image
//                    ? url($product->image) // Will become http://yourdomain.com/images/example.svg
//                    : null;
//                dd($imagePath);
            }
        }

        return Inertia::render('Inquiry/Step4', [
            'inquiry' => $inquiry,
            'categories' => $categories->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'image' => $cat->image
                    ? asset('storage/' . $cat->image) // this becomes: /storage/images/filename.svg
                    : null,
            ]),
            'inventoryItems' => $inventoryItems,
        ]);
    }


    public function step4Store(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'inventory' => 'required|array',
            'inventory.*.item' => 'required|string',
            'inventory.*.quantity' => 'required|integer|min:1',
            'inventory.*.category' => 'required|string',
            'number_of_people' => 'required|integer|min:0',
            'length_of_residence' => 'required|string',
            'number_of_boxes' => 'required|integer|min:0',
            'furniture_assembly'          => 'sometimes|boolean',
            'furniture_lift'              => 'sometimes|boolean',
            'wardrobe_boxes'              => 'sometimes|boolean',
            'wardrobe_boxes_count'        => 'nullable|integer|min:1',
            'box_packing'                 => 'sometimes|boolean',
            'lamp_dismantling'            => 'sometimes|boolean',
            'lamp_dismantling_count'      => 'nullable|integer|min:1',
            'item_disposal'               => 'sometimes|boolean',
            'floor_protection'            => 'sometimes|boolean',
            'floor_protection_count'      => 'nullable|integer|min:10',
        ]);
        ///dd($validated);

        $inventory = $request->input('inventory'); // get full input, including dynamic fields

        foreach ($inventory as &$item) {
            // Keep only non-null fields that are either required or are valid options
            foreach ($item as $key => $value) {
                $isCoreField = in_array($key, ['item', 'category', 'quantity', 'itemImage']);
                $isOptionField = str_starts_with($key, 'option_');

                if (!$isCoreField && !$isOptionField) {
                    unset($item[$key]); // Remove unexpected fields like `size`, `doors`, etc.
                } elseif (($value === null || $value === '') && $isOptionField) {
                    unset($item[$key]); // Remove empty/null option values
                }
            }
        }


        $inquiry->update([
            'inventory' => $inventory, // store full enriched inventory with options
            'number_of_people'        => $validated['number_of_people'],
            'length_of_residence'     => $validated['length_of_residence'],
            'number_of_boxes'         => $validated['number_of_boxes'],
            'furniture_assembly'      => $validated['furniture_assembly'] ?? false,
            'furniture_lift'          => $validated['furniture_lift'] ?? false,
            'wardrobe_boxes'          => $validated['wardrobe_boxes'] ?? false,
            'wardrobe_boxes_count'    => $validated['wardrobe_boxes_count'] ?? null,
            'box_packing'             => $validated['box_packing'] ?? false,
            'lamp_dismantling'        => $validated['lamp_dismantling'] ?? false,
            'lamp_dismantling_count'  => $validated['lamp_dismantling_count'] ?? null,
            'item_disposal'           => $validated['item_disposal'] ?? false,
            'floor_protection'        => $validated['floor_protection'] ?? false,
            'floor_protection_count'  => $validated['floor_protection_count'] ?? null,
        ]);



        return redirect()->route('inquiry.step5', ['inquiry' => $inquiry->id]);
        //return Inertia::location(route('inquiry.step5', ['inquiry' => $inquiry->id]));

    }


    //  STEP 5
    public function step5(Inquiry $inquiry)
    {
        return Inertia::render('Inquiry/Step5', ['inquiry' => $inquiry]);
    }
    public function step5Store(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'inquiry_id'        => 'nullable|exists:inquiries,id',
            'moving_date'       => 'required|date',
            'gender'            => 'required|string|in:Mr,Ms',
            'name'              => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'phone_number'      => 'required|string|min:10|max:15',
            'email'             => 'nullable|email',
            'thirdParty_broker' => 'nullable|boolean',
        ]);

        $validated['gender'] = strtolower($validated['gender']); // 'Mr' -> 'mr'


        // Ensure a user is authenticated, or create one if not
        $user = auth()->user();

        if (!$user) {

            // If user is not authenticated, create a new user with provided email and other details
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name'        => $validated['name'],
                    'last_name'   => $validated['last_name'] ?? 'Not Provided', // Default value for last_name
                    'gender'      => $validated['gender'] ?? 'Not Provided',     // Default value for gender
                    'phone_number'=> $validated['phone_number'],
                    'password'    => bcrypt(uniqid()), // Temporary password if needed
                ]
            );
        } else {

            // If the user is authenticated, update their information
            $user->update([
                'name'         => $validated['name'],
                'last_name'    => $validated['last_name'] ?? 'Not Provided',
                'gender'       => $validated['gender'] ?? 'Not Provided',
                'phone_number' => $validated['phone_number'],
            ]);
        }

        // Attach user_id to validated data for storing in Inquiry
        $validated['user_id'] = $user->id;



        // Check if the inquiry exists or create a new one
        if ($validated['inquiry_id']) {
            // Update the existing inquiry
            $inquiry = Inquiry::findOrFail($validated['inquiry_id']);
            $inquiry->update([
                'moving_date'      => $validated['moving_date'],
                'thirdParty_broker'=> $validated['thirdParty_broker'],
                'user_id'          => $user->id,
            ]);
        } else {
            // Create a new inquiry
            $inquiry = Inquiry::create(array_merge($validated, ['user_id' => $user->id]));
        }


        return redirect()->route('inquiry.thankYou', ['inquiry' => $inquiry->id]);
    }

    public function thankYou(Inquiry $inquiry)
    {
        return Inertia::render('Inquiry/ThankYou', [
            'inquiry' => $inquiry,
        ]);
    }public function thankYouStore(Inquiry $inquiry)
    {
        return redirect()->route('inquiry.thankYou', ['inquiry' => $inquiry->id]);
    }



//    public function step4Store(Request $request, Inquiry $inquiry)
//    {
//        $validated = $request->validate([
//            'inventory' => 'array',
//            'inventory.*.room' => 'required|string',
//            'inventory.*.item' => 'required|string',
//            'inventory.*.quantity' => 'required|integer|min:1',
//            'inventory.*.inventory_items' => 'nullable|array',
//        ]);
//
//        // Remove existing inventory and insert new one
//        $inquiry->inventoryItems()->delete();
//        foreach ($validated['inventory'] as $item) {
//            InventoryItems::create([
//                'id' => $inquiry->id,
//                'room' => $item['room'],
//                'item' => $item['item'],
//                'quantity' => $item['quantity'],
//                'inventory_items'    => $item['inventory_items'] ?? [],
//            ]);
//        }
//
//        $inquiry->update($validated);
//
//       // return redirect()->route('inquiry.step5', ['inquiry' => $inquiry->id]);
//        return Inertia::location(route('inquiry.step5', ['inquiry' => $inquiry->id]));
//    }


}
