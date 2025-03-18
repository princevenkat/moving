<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Enums\VendorStatusEnum;
use App\Filament\Resources\VendorResource\Pages;
use App\Models\User;
use App\Models\Vendor;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BooleanColumn;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class VendorResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getBreadcrumb(): string
    {
        return 'Vendors'; // Custom breadcrumb
    }
    protected static ?string $navigationLabel = 'Vendors'; // Label in the sidebar
    protected static ?string $navigationIcon = 'heroicon-o-user-group'; // Icon in the sidebar

    protected static ?string $navigationGroup = 'Users Management'; // Group in the sidebar
    protected static ?int $navigationSort = 2; // Controls the position of the resource in the sidebar

    // In the VendorResource or any other place
    public static function canCreate(): bool
    {
        return auth()->user()->can('create vendors');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([

                TextInput::make('name')->required(),
                TextInput::make('last_name')->label('Last Name'),
                TextInput::make('email')->email()->unique(ignoreRecord: true)->required(),
                TextInput::make('password')
                    ->password()
                    ->nullable()
                    ->default(fn () => Str::random(12)), // Generate random password

                // ✅ Fix Vendor Data Binding to avoid null issue
                TextInput::make('vendor.store_name')
                    ->label('Company Name')
                    ->required()
                    ->default(fn ($record) => $record?->vendor?->store_name ?? '')
                    ->afterStateHydrated(fn ($component, $record) => $component->state($record?->vendor?->store_name ?? '')),

                TextInput::make('vendor.store_phone')
                    ->label('Phone Number')
                    ->nullable()
                    ->default(fn ($record) => $record?->vendor?->store_phone ?? '')
                    ->afterStateHydrated(fn ($component, $record) => $component->state($record?->vendor?->store_phone ?? '')),

                TextInput::make('vendor.store_address')
                    ->label('Store Address')
                    ->nullable()
                    ->default(fn ($record) => $record?->vendor?->store_address ?? '')
                    ->afterStateHydrated(fn ($component, $record) => $component->state($record?->vendor?->store_address ?? '')),

                Select::make('vendor.status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                    ])
                    ->default(fn ($record) => $record?->vendor?->status ?? 'Pending')
                    ->afterStateHydrated(fn ($component, $record) => $component->state($record?->vendor?->status ?? 'Pending')),
            ]);
    }
//
//    public static function create(CreateRecord $page)
//    {
//        $page->record->password = Hash::make($page->record->password);
//        $page->record->assignRole(RolesEnum::Vendor);
//
//    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return User::query()->whereHas('roles', function ($query) {
            $query->where('name', RolesEnum::Vendor->value); // Ensure this matches your role name
        });
    }
    public static function create(CreateRecord $page)    {

    }


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name'),
                TextColumn::make('vendor.store_name')->label('Company Name'),
                TextColumn::make('vendor.store_phone')->label('Phone'),
                IconColumn::make('vendor.status')
                    ->label('Status')
                    ->sortable()
                    ->options([
                        'heroicon-o-check-circle' => 'Approved', // Green check icon for Approved
                        'heroicon-o-clock' => 'Pending', // Clock icon for Pending
                    ])
                    ->colors([
                        'success' => 'Approved', // Green for Approved
                        'warning' => 'Pending',  // Yellow for Pending
                    ]),

            ])
            ->filters([
                // You can add filters here to sort vendors by status or other attributes.
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Filament::auth()->user();
        return  $user && $user->hasRole(RolesEnum::Admin);
    }
}
