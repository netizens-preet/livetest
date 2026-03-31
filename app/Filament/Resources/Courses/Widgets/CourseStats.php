<?php

namespace App\Filament\Resources\Courses\Widgets;

use App\Models\Course;
use App\Models\Lesson;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {

        return [
            Stat::make('Total Courses', Course::count())
                ->description('Active in library')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Total Lessons', Lesson::count())
                ->description('Across all courses')
                ->descriptionIcon('heroicon-m-play-circle')
                ->color('success'),

            Stat::make('Published', Course::where('is_published', true)->count())
                ->description('Live on website')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('warning'),
        ];

    }
}
