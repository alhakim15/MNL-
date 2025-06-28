<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class DailyActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Pengiriman 7 Hari Terakhir';

    protected static string $color = 'success';

    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $days = collect();
        $deliveryCounts = collect();

        // Get last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->format('D, M j');
            $deliveryCount = Delivery::whereDate('created_at', $date->toDateString())->count();

            $days->push($dayName);
            $deliveryCounts->push($deliveryCount);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengiriman Harian',
                    'data' => $deliveryCounts->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointBackgroundColor' => 'rgba(34, 197, 94, 1)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 6,
                ],
            ],
            'labels' => $days->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'hoverRadius' => 8,
                ],
            ],
        ];
    }
}
