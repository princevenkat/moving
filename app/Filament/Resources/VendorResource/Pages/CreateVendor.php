<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Enums\RolesEnum;
use App\Filament\Resources\VendorResource;
use App\Models\User;
use App\Models\Vendor;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateVendor extends CreateRecord
{
    protected static string $resource = VendorResource::class;



    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $this->data['last_name'] = $this->data['last_name'] ?? '';
        // ✅ Step 1: Create the User
        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // ✅ Step 2: Assign Vendor Role
        $user->assignRole(RolesEnum::Vendor->value);

        // ✅ Step 3: Create Vendor (linked to User)
        return Vendor::create([
            'user_id' => $user->id, // Store user_id in vendors table
            'store_name' => $data['store_name'],
            'store_phone' => $data['store_phone'],
            'store_address' => $data['store_address'],
            'status' => $data['status'],
        ]);
        // Custom handling or logging after creation
        // You can also manipulate the data before saving
        return Vendor::create($data);
    }
}
