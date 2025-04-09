<?php
namespace App\Filament\Resources\InquiryResource\Pages;

use App\Filament\Resources\InquiryResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Form;

class ViewInquiry extends ViewRecord
{
    protected static string $resource = InquiryResource::class;

    public function getHeaderActions(): array
    {
        return [
            Action::make('Create Quote')
                ->modalHeading('Create Quote')
                ->form([
                    TextInput::make('quote_price')
                        ->label('Quote Price')
                        ->numeric()
                        ->required(),

                    Textarea::make('quote_notes')
                        ->label('Notes')
                        ->rows(3),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'declined' => 'Declined',
                        ])
                        ->default('pending'),
                ])
                ->action(fn (array $data) => $this->saveQuote($data)), // Calls save function
        ];
    }


    // ✅ Fix: Use Infolist only for displaying inquiry details
    public function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist->schema([
            Grid::make(10)
                ->schema([
                    Section::make('Inquiry Details')
                        ->schema([
                            TextEntry::make('email')->label('Email')->default('N/A'),
                            TextEntry::make('service_type')->label('Service Type')->default('N/A'),
                            TextEntry::make('current_location')
                                ->label('Moving From')
                                ->formatStateUsing(fn ($record) =>
                                "{$record->current_country}, {$record->current_zip}, {$record->current_city}"
                                )
                                ->default('N/A'),
                            TextEntry::make('new_location')
                                ->label('Moving To')
                                ->formatStateUsing(fn ($record) =>
                                "{$record->destination_country}, {$record->destination_zip}, {$record->destination_city}"
                                )
                                ->default('N/A'),
                            TextEntry::make('notes')->label('Additional Notes')->default('No notes provided'),
                        ])
                        ->columnSpan(10),
                ]),
        ]);
    }

    protected function saveQuote(array $data)
    {
        $this->record->quote()->create($data);
        $this->notify('success', 'Quote created successfully!');
    }

}
