<?php

namespace App\Filament\Resources\DeliveryResource\Widgets;

use App\Models\Delivery;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class DeliveryOverviewWidget extends ChartWidget
{
    protected static ?string $heading = 'Overview Pengiriman';

    protected static string $color = 'info';

    protected function getData(): array
    {
        // Get weekly delivery data for the last 4 weeks
        $weeks = collect();
        $deliveryCounts = collect();

        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $weekLabel = 'Week ' . $startOfWeek->format('M j');
            $deliveryCount = Delivery::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();

            $weeks->push($weekLabel);
            $deliveryCounts->push($deliveryCount);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengiriman Mingguan',
                    'data' => $deliveryCounts->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(99, 102, 241, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(168, 85, 247, 1)',
                    ],
                    'borderWidth' => 2,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $weeks->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
