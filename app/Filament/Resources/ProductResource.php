<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $inventory = $form->model(); // ✅ Corrected

        return $form->schema([
            /** 📌 TABS NAVIGATION **/
            Forms\Components\Tabs::make('Product Details')
                ->columnSpan('full')
                ->tabs(function (callable $get) use ($inventory) {
                    $selectedOptions = is_array($get('options')) ? $get('options') : json_decode($get('options'), true) ?? [];

                    return [
                        /** ✅ GENERAL DETAILS TAB **/
                        Forms\Components\Tabs\Tab::make('General')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('parent_id')
                                    ->options(fn () => Product::where('id', optional($inventory)->id)->pluck('name', 'id')->toArray()) // ✅ Fixed
                                    ->label('Parent Inventory')
                                    ->preload()
                                    ->hidden()
                                    ->searchable(),

                                /** ✅ PRODUCT OPTIONS CHECKBOX LIST **/
                                CheckboxList::make('options')
                                    ->columnSpan('full')
                                    ->label('Product Options')
//                                    ->options([
//                                        'type' => 'Type',
//                                        'size' => 'Size',
//                                        'weight' => 'Weight',
//                                        'doors' => 'Doors',
//                                        'rear-walls' => 'Rear Walls',//
//                                    ])
                                    ->options([
                                        'type' => 'Type',
                                        'size' => 'Size',
                                        'weight' => 'Weight',
                                        'doors' => 'Doors',
                                        'rear-walls' => 'Rear Walls',
                                        'material' => 'Material',
                                        'rear-wall' => 'Rear Wall',
                                        'content' => 'Content',
                                        'nailed' => 'Nailed',
                                        'gas-grill' => 'Gas Grill',
                                    ])
                                    ->reactive()
                                    ->columns(6)
                                    ->afterStateUpdated(fn (callable $set, $state) => $set('options', (array) $state)), // ✅ Ensure it's stored as an array


                    Forms\Components\FileUpload::make('image')
                                    ->columnSpan('full')
                                    ->label('Upload Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('images')
                                    ->nullable()
                                    ->maxSize(1024)
                                    ->helperText('Only image files are allowed'),
                            ]),

                        /** ✅ SHOW "OPTION VALUES" TAB ONLY IF OPTIONS ARE SELECTED **/
                        ...(!empty($selectedOptions) ? [
                            Forms\Components\Tabs\Tab::make('Product Info')
                                ->schema([
                                    Repeater::make('option_values')
                                        ->label('Option Values')
                                        ->schema(function (callable $get) {
                                            $selectedOptions = $get('options') ?? [];

                                            $predefinedOptions = [
                                                'type' => ["Dismantlable", "Non dismantlable"],
                                                'size' => ["Up to 1.2m long", "Up to 1.8m long", "Up to 2.4m long"],
                                                'weight' => ["Up to 60kg", "60kg to 100kg", "100kg to 150kg", "More than 150kg"],
                                                'rear-walls' => ["nailed rear wall", "non-nailed rear wall"],
                                                'doors' => ["Normal doors", "Sliding doors"],
                                            ];

                                            return array_map(fn($option) =>
                                            Repeater::make("option_{$option}")
                                                ->collapsible()
                                                ->label(ucwords(str_replace('_', ' ', $option)))
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
                                        ->afterStateHydrated(function ($component, $state) {
                                            if (is_string($state)) {
                                                $component->state(json_decode($state, true) ?? []);
                                            }
                                        })
                                        ->columns(1),
                                ])
                        ] : []),
                    ];
                }),
        ]);
    }


//    public static function form(Form $form): Form
//    {
//        return $form
//            ->schema([
//                Forms\Components\TextInput::make('name')
//                    ->required()
//                    ->unique()
//                    ->label('Product Name'),
//
//                Select::make('categories')
//                    ->relationship('categories', 'name')
//                    ->multiple()
//                    ->searchable()
//                    ->preload()
//                    ->label('Categories')
//            ]);
//    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable()->label('Product Name'),
                TextColumn::make('categories.name')->sortable()->searchable()->label('Categories')->limit(3),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
