<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Status;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsOverview extends StatsOverviewWidget
{
    // Spans all 3 columns (Full Width)
protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Active User', User::where('status','active')->count())
             ->description('All active accounts')
             ->color('success')
             ->descriptionIcon('heroicon-m-user'),


            Stat::make('Suspended User', User::where('status','suspended')->count())
             ->description('All suspended accounts')
             ->color('warning')
             ->descriptionIcon('heroicon-m-user'),

             Stat::make('Banned User', User::where('status','banned')->count())
             ->description('All Banned accounts')
             ->color('danger')
             ->descriptionIcon('heroicon-m-user'),

             Stat::make('Verified User', User::whereNotNull('email_verified_at')->count())
             ->description('All Verified accounts')
             ->color('gray')
             ->descriptionIcon('heroicon-m-user'),


        ];
    }
}
