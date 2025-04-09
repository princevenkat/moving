<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Filament\Resources\InquiryResource\RelationManagers;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Actions\ViewAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;


class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
//                TextInput::make('email')
//                    ->label('User Name')
//                    ->disabled()
//                    ->default(fn ($record) => $record?->user?->name ?? 'N/A'),
//
//                TextInput::make('service_type')
//                    ->label('Service Type')
//                    ->disabled()
//                    ->default(fn ($record) => $record?->service ?? 'N/A'),
//
//                TextInput::make('moving_from')
//                    ->label('Moving From')
//                    ->disabled()
//                    ->default(fn ($record) => $record?->moving_from ?? 'N/A'),
//
//                TextInput::make('moving_to')
//                    ->label('Moving To')
//                    ->disabled()
//                    ->default(fn ($record) => $record?->moving_to ?? 'N/A'),
//
//                Textarea::make('notes')
//                    ->label('Additional Notes')
//                    ->disabled()
//                    ->default(fn ($record) => $record?->notes ?? 'No notes provided'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('service_type')->label('Service'),
                TextColumn::make('status')->label('Status')->badge(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                //
            ])

            ->actions([
                //Tables\Actions\EditAction::make(),
                ViewAction::make()
                    ->icon('heroicon-o-eye') // 👁 View icon
                    ->label('View')
                    ->tooltip('View Inquiry Details'),
                Action::make('Create Quote')
                    ->url(fn ($record) => QuoteResource::getUrl('create', ['inquiry' => $record->id]))
                    ->visible(fn ($record) => auth()->user()->isVendor())
                    ->label('Create Quote')
                    ->icon('heroicon-o-document-text'),

//                Action::make('Create Quote')
//                    ->url(fn ($record) => QuoteResource::getUrl('create', ['inquiry' => $record->id]))
//                    ->visible(fn ($record) => auth()->user()->isVendor()),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
            'index' => Pages\ListInquiries::route('/'),
            'view' => Pages\ViewInquiry::route('/{record}'),
            //'create' => Pages\CreateInquiry::route('/create'),
            //'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }



    public static function getEloquentQuery(): Builder
    {
       // return parent::getEloquentQuery();
        return parent::getEloquentQuery()->with('user');

    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }


    public static function viewRecord(ViewRecord $page)
    {
        return $page->record(fn ($record) => $record->load('user')); // Ensure data is loaded
    }


}
