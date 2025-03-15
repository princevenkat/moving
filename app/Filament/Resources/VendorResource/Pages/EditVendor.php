<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Enums\RolesEnum;
use App\Filament\Resources\VendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditVendor extends EditRecord
{
    protected static string $resource = VendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Vendor'; // Custom heading for listing page
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function handleRecordRetrieval(\Illuminate\Database\Eloquent\Model $record): \Illuminate\Database\Eloquent\Model
    {
        return $record->load(RolesEnum::Vendor->value); // ✅ Ensure vendor relationship is loaded
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // Update user details
        $record->update([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        // Update password only if provided
        if (!empty($data['password'])) {
            $record->update(['password' => Hash::make($data['password'])]);
        }

        // ✅ Ensure vendor record exists before updating
        if ($record->vendor) {
            $record->vendor->update([
                'store_name' => $data['vendor']['store_name'] ?? null,
                'store_phone' => $data['vendor']['store_phone'] ?? null,
                'store_address' => $data['vendor']['store_address'] ?? null,
                'status' => $data['vendor']['status'] ?? 'Pending',
            ]);
        } else {
            // Create vendor record if it doesn’t exist
            $record->vendor()->create([
                'store_name' => $data['vendor']['store_name'] ?? null,
                'store_phone' => $data['vendor']['store_phone'] ?? null,
                'store_address' => $data['vendor']['store_address'] ?? null,
                'status' => $data['vendor']['status'] ?? 'Pending',
            ]);
        }

        return $record;
    }

}
