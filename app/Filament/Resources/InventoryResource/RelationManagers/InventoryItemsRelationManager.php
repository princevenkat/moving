<?php

namespace App\Filament\Resources\InventoryResource\RelationManagers;

use App\Models\InventoryItems;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
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
                ->searchable(),
                Forms\Components\FileUpload::make('image')  // 'image' is the field name
                ->label('Upload Image')
                ->image()  // This ensures the uploaded file is an image
                ->disk('public')  // Store the file in the 'public' disk (you can change it based on your configuration)
                ->directory('images')  // Save the image in a folder named 'images'
                ->required()  // Make it required if needed
                ->maxSize(1024)  // Max file size in KB (optional)
                ->helperText('Only image files are allowed'), // Custom helper text (optional)

                Forms\Components\Checkbox::make('active')
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
