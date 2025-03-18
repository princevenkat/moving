<?php

namespace App\Filament\Resources\InventoryResource\RelationManagers;

use App\Models\InventoryItems;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'Items';

    public function form(Form $form): Form
    {
        $inventory = $this->getOwnerRecord();

        return $form
            ->schema([
                /** 📌 TABS NAVIGATION **/
                Forms\Components\Tabs::make('Product Details')
                    ->columnSpan('full')
                    ->tabs(function (callable $get) use ($inventory) {
                        $selectedOptions = $get('options') ?? []; // Get selected product options

                        return [
                            /** ✅ GENERAL DETAILS TAB **/
                            Forms\Components\Tabs\Tab::make('General')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),

                                    Forms\Components\Select::make('parent_id')
                                        ->options(function () use ($inventory) {
                                            return InventoryItems::query()
                                                ->where('inventory_id', $inventory->id)
                                                ->pluck('name', 'id')
                                                ->toArray();
                                        })
                                        ->label('Parent Inventory')
                                        ->preload()
                                        ->hidden()
                                        ->searchable(),
                                    Forms\Components\Checkbox::make('active')
                                        ->label('Active'),

                                    /** ✅ PRODUCT OPTIONS CHECKBOX LIST **/
                                    CheckboxList::make('options')
                                        ->columnSpan('full')
                                        ->label('Product Options')
                                        ->options([
                                            'type' => 'Type',
                                            'size' => 'Size',
                                            'weight' => 'Weight',
                                            'doors' => 'Doors',
                                            'rear-walls' => 'Rear Walls',
                                        ])
                                        ->reactive()
                                        ->columns(6),

                                    Forms\Components\FileUpload::make('image')
                                        ->columnSpan('full')
                                        ->label('Upload Image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('images')
                                        ->required()
                                        ->maxSize(1024)
                                        ->helperText('Only image files are allowed'),




                                ]),

                            /** ✅ SHOW "OPTION VALUES" TAB ONLY IF OPTIONS ARE SELECTED **/
                            ...(!empty($selectedOptions) ? [
                                Forms\Components\Tabs\Tab::make('Product Info')
                                    ->schema([

                                        Repeater::make('option_values')
                                            ->minItems(1)
                                            ->maxItems(1)
                                            ->label('Option Values')
                                            ->schema(function () use ($selectedOptions) {
                                                $predefinedOptions = [
                                                    'type' => ["Dismantlable", "Non dismantlable"],
                                                    'size' => ["Up to 1.2m long", "Up to 1.8m long", "Up to 2.4m long"],
                                                    'weight' => ["Up to 60kg", "60kg to 100kg", "100kg to 150kg", "More than 150kg"],
                                                    'rear-walls' => ["nailed rear wall", "non-nailed rear wall"],
                                                    'doors' => ["Normal doors", "Sliding doors"],
                                                ];

                                                return array_map(fn ($option) =>
                                                Repeater::make("option_{$option}") // Separate repeater for each option
                                                ->collapsible()

                                                ->label(ucwords(str_replace('-', ' ', $option)))
                                                    ->schema([
                                                        Select::make("value")
                                                            ->label('Select Value')
                                                            ->options($predefinedOptions[$option] ?? [])
                                                            ->searchable()
                                                            ->preload(),

                                                        TextInput::make("custom_value")
                                                            ->label('Or Add Custom Value')
                                                            ->placeholder('Enter custom value')
                                                            ->helperText('If the value is not in the list, enter a custom one.'),
                                                    ])
                                                    ->columns(2),
                                                    $selectedOptions
                                                );
                                            })
                                            ->columns(1),
                                    ])
                            ] : []), // Option Values tab only appears if product options are selected
                        ];
                    }),


//                Forms\Components\TextInput::make('name')
//                    ->required()
//                    ->maxLength(255),
//
//                Forms\Components\Select::make('parent_id')
//                    ->options(function () use ($inventory) {
//                        return InventoryItems::query()
//                            ->where('inventory_id', $inventory->id)
//                            ->pluck('name', 'id')
//                            ->toArray();
//                    })
//                    ->label('Parent Inventory')
//                    ->preload()
//                    ->searchable(),
//
//
//
////                CheckboxList::make('options')
////                    ->label('Product Options')
////                    ->options([
////                        'type' => 'Type',
////                        'size' => 'Size',
////                        'weight' => 'Weight',
////                        'doors' => 'Doors',
////                        'rear-walls' => 'Rear Walls',
////                    ]),
////
////                Repeater::make('option_values')
////                    ->label('Option Values')
////                    ->schema([
////                        Select::make('option')
////                            ->options([
////                                'type' => 'Type',
////                                'size' => 'Size',
////                                'weight' => 'Weight',
////                                'doors' => 'Doors',
////                                'rear-walls' => 'Rear Walls',
////                            ])
////                            ->reactive()
////                            ->required(),
////
////
////                        Select::make('value')
////                            ->options(function (callable $get) {
////                                $predefinedOptions = [
////                                    'type' => ["Dismantlable", "Non dismantlable"],
////                                    'size' => ["Up to 1.2m long", "Up to 1.8m long", "Up to 2.4m long"],
////                                    'weight' => ["Up to 60kg", "60kg to 100kg", "100kg to 150kg", "More than 150kg"],
////                                    'rear-walls' => ["nailed rear wall", "non-nailed rear wall"],
////                                    'doors' => ["Normal doors", "Sliding doors"],
////                                ];
////                                return $predefinedOptions[$get('option')] ?? [];
////                            })
////                            ->required(),
////                    ])
////                    ->columns(2),
//
////                CheckboxList::make('options')
////                    ->label('Product Options')
////                    ->options([
////                        'type' => 'Type',
////                        'size' => 'Size',
////                        'weight' => 'Weight',
////                        'doors' => 'Doors',
////                        'rear-walls' => 'Rear Walls',
////                    ])
////                    ->reactive()
////                    ->columns(1),
////
////                Repeater::make('option_values')
////                    ->label('Option Values')
////                    ->hidden(fn (callable $get) => empty($get('options'))) // Hide if no options are selected
////                    ->schema(function (callable $get) {
////                        $selectedOptions = $get('options') ?? [];
////                        $predefinedOptions = [
////                            'type' => ["Dismantlable", "Non dismantlable"],
////                            'size' => ["Up to 1.2m long", "Up to 1.8m long", "Up to 2.4m long"],
////                            'weight' => ["Up to 60kg", "60kg to 100kg", "100kg to 150kg", "More than 150kg"],
////                            'rear-walls' => ["nailed rear wall", "non-nailed rear wall"],
////                            'doors' => ["Normal doors", "Sliding doors"],
////                        ];
////
////                        return array_map(fn ($option) =>
////                        Repeater::make("option_{$option}") // Separate repeater for each option
////                        ->label(ucwords(str_replace('-', ' ', $option)))
////                            ->schema([
////                                Select::make("value")
////                                    ->label('Select Value')
////                                    ->options($predefinedOptions[$option] ?? [])
////                                    ->searchable()
////                                    ->preload(),
////
////                                TextInput::make("custom_value")
////                                    ->label('Or Add Custom Value')
////                                    ->placeholder('Enter custom value')
////                                    ->helperText('If the value is not in the list, enter a custom one.'),
////                            ])
////                            ->columns(1),
////                            $selectedOptions
////                        );
////                    })
////                    ->columns(1),
//
//
//
//                Forms\Components\FileUpload::make('image')  // 'image' is the field name
//                    ->label('Upload Image')
//                    ->image()  // This ensures the uploaded file is an image
//                    ->disk('public')  // Store the file in the 'public' disk (you can change it based on your configuration)
//                    ->directory('images')  // Save the image in a folder named 'images'
//                    ->required()  // Make it required if needed
//                    ->maxSize(1024)  // Max file size in KB (optional)
//                    ->helperText('Only image files are allowed'), // Custom helper text (optional)
//
//                Forms\Components\Checkbox::make('active')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),
//                Tables\Columns\TextColumn::make('parent.name')
//                ->sortable()
//                ->searchable(),
                ImageColumn::make('image')
                    ->square(),
                IconColumn::make('active')
                ->boolean()

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
