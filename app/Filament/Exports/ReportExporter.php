<?php

namespace App\Filament\Exports;

use App\Models\Report;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ReportExporter extends Exporter
{
    protected static ?string $model = Report::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('category'),
            ExportColumn::make('location'),
            ExportColumn::make('time'),
            ExportColumn::make('event_date'),
            ExportColumn::make('constituency'),
            ExportColumn::make('description'),
            ExportColumn::make('status'),
            ExportColumn::make('ip_address'),
//            ExportColumn::make('user_agent'),
            ExportColumn::make('image'),
            ExportColumn::make('url'),
//            ExportColumn::make('evidence'),
            ExportColumn::make('contact'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your report export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
