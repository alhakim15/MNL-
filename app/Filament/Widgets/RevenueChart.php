<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Estimasi Pendapatan Bulanan';

    protected static string $color = 'warning';

    protected static ?int $sort = 8;

    protected function getData(): array
    {
        $months = collect();
        $revenues = collect();

        // Average price per ton (adjust based on your business model)
        $avgPricePerTon = 1000000; // 1 juta rupiah per ton

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');

            $totalWeight = Delivery::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('weight');

            $revenue = $totalWeight * $avgPricePerTon;

            $months->push($monthName);
            $revenues->push($revenue / 1000000); // Convert to millions
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Juta Rupiah)',
                    'data' => $revenues->toArray(),
                    'backgroundColor' => [
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(252, 211, 77, 0.8)',
                        'rgba(253, 224, 71, 0.8)',
                        'rgba(254, 240, 138, 0.8)',
                        'rgba(255, 251, 235, 0.8)',
                    ],
                    'borderColor' => 'rgba(245, 158, 11, 1)',
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $months->toArray(),
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
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value + "M"; }'
                    ]
                ],
            ],
            'elements' => [
                'bar' => [
                    'borderWidth' => 2,
                ],
            ],
        ];
    }
}
