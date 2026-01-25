<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\Report;
use Carbon\Carbon;

class ReportsChart extends ChartWidget
{
    protected ?string $heading = 'Reports Submitted (Hourly)';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // Initialize 24 hours array
        $hours = [];
        $counts = [];
        for ($i = 0; $i < 24; $i++) {
            $hours[] = sprintf('%02d:00', $i); // Hour labels 00:00, 01:00, ..., 23:00
            $counts[$i] = 0;                   // Default count 0
        }

        // Get today's reports
        $reports = Report::whereDate('created_at', Carbon::today())->get();

        // Count reports per hour
        foreach ($reports as $report) {
            $hour = (int) $report->created_at->format('H');
            $counts[$hour]++;
        }

        return [
            'labels' => $hours,
            'datasets' => [
                [
                    'label' => 'Reports per Hour',
                    'data' => array_values($counts),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
        ];
    }
}
