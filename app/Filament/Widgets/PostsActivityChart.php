<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class PostsActivityChart extends ChartWidget
{
    protected ?string $heading = 'Posts Activity — Last 30 Days';

    // Spans the remaining 1 column
    protected int|string|array $columnSpan = 1;

    // Polling interval must be static
    protected ?string $pollingInterval = '60s';

    // Set the color to primary (non-static)
    protected string $color = 'primary';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $start = match ($activeFilter) {
            'today' => now()->startOfDay(),
            'week' => now()->subDays(6),
            'month' => now()->subDays(29),
            'year' => now()->startOfYear(),
            default => now()->subDays(29),
        };
        $data = Trend::model(Post::class)
            ->between(
                start: $start,
                end: now(),
            )
            ->{$activeFilter === 'today' ? 'perHour' : 'perDay'}()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Posts Created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => match ($activeFilter) {
                'today' => Carbon::parse($value->date)->format('H:i'),
                'year' => Carbon::parse($value->date)->format('M'),
                default => Carbon::parse($value->date)->format('d M'),
            }),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // You can also use 'line'
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
}
