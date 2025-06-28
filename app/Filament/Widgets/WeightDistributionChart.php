<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use Filament\Widgets\ChartWidget;

class WeightDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Berat Pengiriman';

    protected static string $color = 'primary';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        // Define weight ranges
        $weightRanges = [
            '0-1 ton' => [0, 1],
            '1-5 ton' => [1, 5],
            '5-10 ton' => [5, 10],
            '10-20 ton' => [10, 20],
            '20+ ton' => [20, PHP_INT_MAX],
        ];

        $rangeLabels = [];
        $rangeCounts = [];
        $colors = [
            'rgba(99, 102, 241, 0.8)',   // Indigo
            'rgba(168, 85, 247, 0.8)',   // Violet
            'rgba(236, 72, 153, 0.8)',   // Pink
            'rgba(251, 146, 60, 0.8)',   // Orange
            'rgba(239, 68, 68, 0.8)',    // Red
        ];

        foreach ($weightRanges as $label => $range) {
            $count = Delivery::where('weight', '>=', $range[0]);

            if ($range[1] !== PHP_INT_MAX) {
                $count = $count->where('weight', '<', $range[1]);
            }

            $count = $count->count();

            if ($count > 0) { // Only show ranges that have data
                $rangeLabels[] = $label;
                $rangeCounts[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $rangeCounts,
                    'backgroundColor' => array_slice($colors, 0, count($rangeCounts)),
                    'borderColor' => array_map(function ($color) {
                        return str_replace('0.8', '1', $color);
                    }, array_slice($colors, 0, count($rangeCounts))),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $rangeLabels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
