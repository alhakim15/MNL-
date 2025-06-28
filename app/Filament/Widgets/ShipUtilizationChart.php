<?php

namespace App\Filament\Widgets;

use App\Models\Ship;
use App\Models\Delivery;
use Filament\Widgets\ChartWidget;

class ShipUtilizationChart extends ChartWidget
{
    protected static ?string $heading = 'Utilisasi Kapal';

    protected static string $color = 'warning';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $ships = Ship::with(['deliveries' => function ($query) {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }])->get();

        $shipNames = [];
        $utilizationData = [];
        $backgroundColors = [];

        foreach ($ships as $ship) {
            $totalWeightThisMonth = $ship->deliveries->sum('weight');
            $utilizationPercentage = $ship->max_weight > 0
                ? round(($totalWeightThisMonth / $ship->max_weight) * 100, 1)
                : 0;

            $shipNames[] = $ship->name;
            $utilizationData[] = $utilizationPercentage;

            // Color coding based on utilization
            if ($utilizationPercentage >= 80) {
                $backgroundColors[] = 'rgba(220, 38, 127, 0.8)'; // Red for high utilization
            } elseif ($utilizationPercentage >= 60) {
                $backgroundColors[] = 'rgba(245, 158, 11, 0.8)'; // Orange for medium utilization
            } else {
                $backgroundColors[] = 'rgba(34, 197, 94, 0.8)'; // Green for low utilization
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Utilisasi (%)',
                    'data' => $utilizationData,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => array_map(function ($color) {
                        return str_replace('0.8', '1', $color);
                    }, $backgroundColors),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $shipNames,
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
                    'max' => 100,
                    'ticks' => [
                        'callback' => 'function(value) { return value + "%"; }'
                    ]
                ],
            ],
        ];
    }
}
