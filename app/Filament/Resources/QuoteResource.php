<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('inquiry_id')->relationship('inquiry', 'id')->label('Inquiry ID')->disabled(),
                Select::make('vendor_id')->relationship('vendor', 'name')->label('Vendor')->disabled(),
                Textarea::make('details')->required()->label('Quote Details'),
                TextInput::make('price')->numeric()->required()->label('Price'),
                Select::make('status')->options([
                    'Pending' => 'Pending',
                    'Accepted' => 'Accepted',
                    'Rejected' => 'Rejected',
                ])->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inquiry.id')->label('Inquiry ID'),
                TextColumn::make('vendor.name')->label('Vendor'),
                TextColumn::make('price')->label('Price')->money(),
                TextColumn::make('status')->label('Status')->badge(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
