<?php

namespace App\Filament\Widgets;
use App\Models\User;
use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->icon('heroicon-o-users')
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                
            Stat::make('Total Tasks', Task::count())
                ->icon('heroicon-o-clipboard-document-list')
                ->description('All tasks created')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
                
            Stat::make('Completed Tasks', Task::where('is_completed', true)->count())
                ->icon('heroicon-o-check-circle')
                ->description('Tasks marked as done')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
                
        ];
    }
}
