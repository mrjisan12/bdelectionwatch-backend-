<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ================= Reporter Info =================
                Section::make('Reporter Info')
                    ->description('Information about the person reporting')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Reporter Name')
                                ->placeholder('Optional')
                                ->columnSpanFull()
                                ->nullable(),

                            TextInput::make('contact')
                                ->label('Contact Details')
                                ->placeholder('Phone number / Whatsapp / Email')
                                ->columnSpanFull(),
                        ]),
                    ]),

                // ================= Report Details =================
                Section::make('Report Details')
                    ->description('Details of the reported incident')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('category')
                                ->label('Category')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('time')
                                ->label('Time')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('location')
                                ->label('Location')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('constituency')
                                ->label('Constituency')
                                ->required()
                                ->columnSpanFull(),

                            Textarea::make('description')
                                ->label('Description')
                                ->rows(4)
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),

                // ================= Media Evidence =================
                Section::make('Media Evidence')
                    ->description('Upload images or videos related to the report')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('image')
                                ->label('Image Evidence')
                                ->image()
                                ->disk('public')
                                ->directory('reports/images')
                                ->columnSpanFull()
                                ->nullable(),

                            TextInput::make('video')
                                ->label('Video URL')
                                ->placeholder('https://...')
                                ->url()
                                ->columnSpanFull()
                                ->nullable(),

                            TextInput::make('url')
                                ->label('Evidence URL')
                                ->placeholder('https://www.jagonews24.com/country/news/...')
                                ->url()
                                ->columnSpanFull()
                                ->nullable(),

                        ]),
                    ]),

                // ================= Verification =================
                Section::make('Verification')
                    ->description('Status and evidence for verifying the report')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'pending' => 'Pending',
                                    'verified' => 'Verified',
                                    'rejected' => 'Rejected',
                                ])
                                ->default('pending')
                                ->required()
                                ->columnSpanFull(),

                            Textarea::make('evidence')
                                ->label('Feedback of Verifying')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
