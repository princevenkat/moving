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
                                   //->options(fn () => Product::where('id', optional($inventory)->id)->pluck('name', 'id')->toArray()) // ✅ Fixed
                                    ->options(fn () => Product::where('id', '!=', optional($inventory)->id)->pluck('name', 'id')->toArray())
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
                                    //->afterStateUpdated(fn (callable $set, $state) => $set('options', (array) $state)), // ✅ Ensure it's stored as an array
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        $values = $get('option_values') ?? [];

                                        foreach ($values as &$value) {
                                            if (!empty($value['custom_value'])) {
                                                $value['value'] = $value['custom_value']; // Use custom_value if not empty
                                            }
                                            unset($value['custom_value']); // Remove it to avoid duplicate storage
                                        }

                                        $set('option_values', $values);
                                    }),

                                Forms\Components\Placeholder::make('options_hint')
                                    ->content('Tip: Select one or more product options to unlock the "Product Info" tab, where you can provide specific details for each option.')
                                    ->columnSpan('full')
                                    ->visible(fn (callable $get) => empty($get('options'))),
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

//                                            $predefinedOptions = [
//                                                'type' => ["Dismantlable", "Non dismantlable"],
//                                                'size' => ["Up to 1.2m long", "Up to 1.8m long", "Up to 2.4m long"],
//                                                'weight' => ["Up to 60kg", "60kg to 100kg", "100kg to 150kg", "More than 150kg"],
//                                                'rear-walls' => ["nailed rear wall", "non-nailed rear wall"],
//                                                'doors' => ["Normal doors", "Sliding doors"],
//                                            ];
                                            $predefinedOptionss = [
                                                'size' => [
                                                    'couch' => ["Length up to 2m", "Length up to 4m", "Length more than 4m"],
                                                    'table' => ["2-4 persons", "6-8 persons", "9+ people"],
                                                    'tv' => ["Up to 40 In (ca. 1m)", "Up to 80 In (ca. 2m)", "More than 80 In"],
                                                    'sideboard' => ["Up to 1.2m long", "Up to 1.8m long", "Up to 2.4m long"],
                                                    'shelf' => ["0.6m x 0.6m", "0.6m x 1.2m", "0.6m x 1.8m", "More than 0.6m x 1.8m", "1.2m x 1.2m", "1.2m x 1.8m", "More than 1.2m x 1.8m", "1.8m x 1.8m", "More than 1.8m x 1.8m"],
                                                    'plant' => ["Up to 1m", "Up to 2m", "More than 2m"],
                                                    'wardrobe' => ["1-2 doors", "3-4 doors", "5-6 doors", "7-8 doors"],
                                                    'corner-couch' => ["Length up to 2m", "Length up to 4m", "Length more than 4m"],
                                                    'drawer' => ["Length up to 1m", "Length up to 2m", "Length more than 2m"],
                                                    'picture' => ["Length up to 1m", "Length up to 2m", "Length more than 2m"],
                                                    'modern-wall-unit' => ["2 parts", "3 parts", "4 parts", "5 parts"],
                                                    'aquarium' => ["60 x 30 x 30 | 54 L", "80 x 35 x 40 | 112 L", "100 x 40 x 40 | 160 L", "120 x 40 x 50 | 240 L", "150 x 50 x 60 | 450 L"],
                                                    'kid-toy' => ["60 x 60 x 60 cm", "1 x 1 x 1 m", "1.5 x 1.5 x 1.5 m"],
                                                    'trampoline' => ["Up to 1m diameter", "Up to 2.5m diameter", "More than 2.5m diameter"],
                                                    'bed' => ["Single bed", "Double bed", "Couch bed", "Loft bed", "Boxspring bed", "Waterbed", "Folding bed", "Canopy bed", "Bunk bed"],
                                                    'grand-piano' => ["Small", "Big"],
                                                    'additional-mattress' => ["Single", "Double"],
                                                    'sculpture' => ["Up to 1m x 1m", "Up to 2m x 2m", "More than 2m x 2m"],
                                                    'long-case-clock' => ["Small", "Big"],
                                                    'safe' => ["Up to 100kg", "Up to 200kg", "More than 200kg"],
                                                    'weight-bench' => ["Up to 100kg", "Up to 200kg", "More than 200kg"],
                                                    'freezer-cabinet' => ["Small", "Big"],
                                                    'deep-freezer' => ["Small", "Big"],
                                                    'wardrobe-sliding' => ["1-2 sliding doors", "3-4 sliding doors", "5+ sliding doors"],
                                                    'country-wardrobe' => ["1 door", "2 doors", "3 doors"],
                                                    'showcase' => ["1 door", "2 doors"],
                                                    'bureau' => ["1-parted", "2-parted"],
                                                    'classic-wall-unit' => ["Up to 2m demountable", "Up to 3m demountable", "Up to 4m demountable", "Up to 5m demountable"],
                                                    'desk' => ["Length up to 2m", "Length more than 2m"],
                                                    'mirror' => ["Length up to 1m", "Length up to 2m", "Length more than 2m"],
                                                    'grill' => ["Length up to 1m", "Length up to 2m", "Length more than 2m"],
                                                ],
                                                'material' => [
                                                    'table' => ["Wood", "Stone / Metal", "Glass"],
                                                    'garden-table' => ["Wood", "Stone / Metal", "Glass"],
                                                ],
                                                'rear-wall' => ["nailed rear wall", "non-nailed rear wall"],
                                                'doors' => ["Normal doors", "Sliding doors"],
                                                'gas-grill' => ["No", "Yes"],
                                                'content' => [
                                                    'flower-pot' => ["Empty", "Full"],
                                                ],
                                            ];

                                            $predefinedOptions = collect($predefinedOptionss)->map(function ($values) {
                                                if (is_array($values)) {
                                                    return collect($values)->map(function ($subValues) {
                                                        if (is_array($subValues)) {
                                                            return collect($subValues)->mapWithKeys(fn($v) => [$v => $v])->toArray();
                                                        }
                                                        return $subValues;
                                                    })->toArray();
                                                }
                                                return $values;
                                            })->toArray();



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
