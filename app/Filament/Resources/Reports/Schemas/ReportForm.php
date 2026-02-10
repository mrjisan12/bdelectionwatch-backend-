<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

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
                                ->placeholder('নির্বাচনী আচরনবিধি লঙ্ঘন')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('time')
                                ->label('Time')
                                ->placeholder('২৫ জানুয়ারি, বিকাল ৫টা থেকে কয়েক ঘন্টা')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('location')
                                ->label('Location')
                                ->placeholder('ময়মনসিংহের ভালুকা বাসস্ট্যান্ড ও হবিরবাড়ি এলাকা')
                                ->required()
                                ->columnSpanFull(),

                            TextInput::make('constituency')
                                ->label('Constituency')
                                ->placeholder('ময়মনসিংহ-১১')
                                ->required()
                                ->columnSpanFull(),

                            Textarea::make('description')
                                ->label('Description')
                                ->placeholder('হবিগঞ্জ-৪ (মাধবপুর-চুনারুঘাট) আসনে আচরণবিধি লঙ্ঘনের অভিযোগে দুই প্রার্থীকে শোকজ করা হয়েছে। নির্বাচনি অনুসন্ধান ও বিচারিক কমিটির চেয়ারম্যান.....')
                                ->rows(4)
                                ->required()
                                ->columnSpanFull(),
                                
                            DatePicker::make('event_date')
                                ->label('ঘটনার তারিখ')
                                ->placeholder('12 Feb 2026')
                                ->required()
                                ->native(false)
                                ->displayFormat('d M Y'),
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
                                ->placeholder('ছবি ও স্থানীয় প্রশাসনের তথ্য মিলিয়ে দেখা হয়েছে।')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
