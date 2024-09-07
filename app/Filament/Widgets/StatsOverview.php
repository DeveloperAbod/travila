<?php

namespace App\Filament\Widgets;

use App\Models\CategoryTour;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval  = '5s';

    protected static ?int $sort = 1;



    protected function getStats(): array
    {

        return [
            Stat::make('Total Users', User::count())
                ->description('Increase in Users')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Tours Categories', CategoryTour::count())
                ->description('Increase in Tours Categories')
                ->descriptionIcon('heroicon-o-squares-2x2')
                ->color('warning')
                ->chart([2, 9, 3, 5, 3, 8, 2, 10]),

            Stat::make('Total Tours', Tour::count())
                ->description('Increase in Tours')
                ->descriptionIcon('heroicon-o-map')
                ->color('info')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Destinations', Destination::count())
                ->description('Increase in Destinations')
                ->descriptionIcon('heroicon-o-map-pin')
                ->color('warning')
                ->chart([7, 19, 4, 5, 6, 20, 9, 10])
        ];
    }
}
