<?php
namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing roles before creating them
        Role::whereIn('name', [RolesEnum::User->value, RolesEnum::Vendor->value, RolesEnum::Admin->value])->delete();

        // Create roles
        $userRole   = Role::create(['name' =>  RolesEnum::User->value]);
        $vendorRole = Role::create(['name' =>  RolesEnum::Vendor->value]);
        $adminRole  = Role::create(['name' =>  RolesEnum::Admin->value]);

        // Create permissions
        $approveVendors = Permission::create([
            'name' => PermissionsEnum::ApproveVendors->value
        ]);

        $sellServices = Permission::create([
            'name' => PermissionsEnum::SellServices->value
        ]);

        $buyServices = Permission::create([
            'name' => PermissionsEnum::BuyServices->value
        ]);

        $createVendors = Permission::create([
            'name' => 'create vendors'
        ]);

        // Assign permissions to roles
        $userRole->syncPermissions([$buyServices]);
        $vendorRole->syncPermissions([$sellServices, $buyServices]);
        $adminRole->syncPermissions([$approveVendors, $sellServices, $buyServices, $createVendors]);
    }
}
