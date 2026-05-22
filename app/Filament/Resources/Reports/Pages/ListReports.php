<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Exports\ReportExporter;
use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\Contracts\ExportFormat;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(ReportExporter::class)
        ];
    }
}
