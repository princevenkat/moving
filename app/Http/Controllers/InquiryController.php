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

use Inertia\Inertia;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function create()
    {
        return Inertia::render('Inquiry/Step1');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required',
            'current_country' => 'required',
            'current_zip' => 'required',
            'current_city' => 'required',
            'destination_country' => 'nullable',
            'destination_zip' => 'nullable',
            'destination_city' => 'nullable',
            'email' => 'required|email',
        ]);

        $inquiry = Inquiry::create($validated);

        return redirect()->route('inquiry.step2', ['inquiry' => $inquiry->id]);
    }

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
            'rooms' => 'nullable|integer',
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
            'new_home_rooms' => 'nullable|integer',
            'new_home_square_meters' => 'nullable|integer',
            'new_home_has_elevator' => 'nullable|string',
            'new_home_distance_meters' => 'nullable|integer',
            'new_home_num_steps' => 'nullable|integer',
            'new_home_impeded_details' => 'nullable|string',
        ]);

        // Ensure valid values for has_elevator
        $validated['new_home_has_elevator'] = in_array(strtolower($validated['new_home_has_elevator']), ['yes', 'no']) ? $validated['has_elevator'] : 'no';

        $inquiry->update($validated);

        return redirect()->route('inquiry.step3', ['inquiry' => $inquiry->id]);
    }


}
