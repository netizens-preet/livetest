<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UserRegistrationsChart extends ChartWidget
{
    protected ?string $heading = 'User Registrations — Last 30 Days';

    protected ?string $pollingInterval = '60s';

    protected string $color = 'success';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Trend::model(User::class)
            ->between(
                start: now()->subDays(29),
                end: now(),
            )
            ->perDay()
            ->dateColumn('created_at')
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'New Users',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
