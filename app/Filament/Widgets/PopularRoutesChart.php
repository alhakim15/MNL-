<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use App\Models\City;
use Filament\Widgets\ChartWidget;

class PopularRoutesChart extends ChartWidget
{
    protected static ?string $heading = 'Rute Pengiriman Terpopuler';

    protected static string $color = 'danger';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Get top 8 most popular routes
        $routes = Delivery::selectRaw('from_city_id, to_city_id, COUNT(*) as total')
            ->with(['fromCity', 'toCity'])
            ->groupBy('from_city_id', 'to_city_id')
            ->orderBy('total', 'desc')
            ->limit(8)
            ->get();

        $routeNames = [];
        $routeCounts = [];
        $colors = [
            'rgba(239, 68, 68, 0.8)',   // Red
            'rgba(245, 158, 11, 0.8)',  // Orange
            'rgba(34, 197, 94, 0.8)',   // Green
            'rgba(59, 130, 246, 0.8)',  // Blue
            'rgba(147, 51, 234, 0.8)',  // Purple
            'rgba(236, 72, 153, 0.8)',  // Pink
            'rgba(14, 165, 233, 0.8)',  // Sky
            'rgba(16, 185, 129, 0.8)',  // Emerald
        ];

        foreach ($routes as $index => $route) {
            $routeName = ($route->fromCity && $route->fromCity->name ? $route->fromCity->name : '-') . ' → ' .
                ($route->toCity && $route->toCity->name ? $route->toCity->name : '-');
            $routeNames[] = $routeName;
            $routeCounts[] = $route->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengiriman',
                    'data' => $routeCounts,
                    'backgroundColor' => array_slice($colors, 0, count($routeCounts)),
                    'borderColor' => array_map(function ($color) {
                        return str_replace('0.8', '1', $color);
                    }, array_slice($colors, 0, count($routeCounts))),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $routeNames,
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
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
            'scales' => [
                'r' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
