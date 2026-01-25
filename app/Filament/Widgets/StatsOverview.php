<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Report;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Fetch counts dynamically
        $total = Report::count();
        $pending = Report::where('status', 'pending')->count();
        $verified = Report::where('status', 'verified')->count();
        $rejected = Report::where('status', 'rejected')->count();

        return [
            Stat::make('Total Reports', $total)
                ->description('People submitted')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Pending', $pending)
                ->description('Waiting for review')
                ->color('warning'),

            Stat::make('Verified', $verified)
                ->description('Approved & Verified')
                ->color('success'),

            Stat::make('Rejected', $rejected)
                ->description('Rejected after reviewing')
                ->color('danger'),
        ];
    }
}
