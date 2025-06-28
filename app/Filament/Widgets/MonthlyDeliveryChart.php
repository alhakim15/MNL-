<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class MonthlyDeliveryChart extends ChartWidget
{
    protected static ?string $heading = 'Pengiriman Bulanan';

    protected static string $color = 'info';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months = collect();
        $deliveries = collect();

        // Get last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            $deliveryCount = Delivery::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $months->push($monthName);
            $deliveries->push($deliveryCount);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengiriman',
                    'data' => $deliveries->toArray(),
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                    ],
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $months->toArray(),
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
