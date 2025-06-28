<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use App\Models\DeliveryStatusLog;
use Filament\Widgets\ChartWidget;

class DeliveryStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Pengiriman';

    protected static string $color = 'success';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Get status counts from latest status logs
        $statusCounts = DeliveryStatusLog::selectRaw('status, COUNT(*) as count')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('delivery_status_logs')
                    ->groupBy('delivery_id');
            })
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Define status labels and colors
        $statusLabels = [
            'pending' => 'Pending',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Dikirim',
            'cancelled' => 'Dibatalkan'
        ];

        $statusColors = [
            'pending' => 'rgba(251, 191, 36, 0.8)',      // Yellow
            'in_transit' => 'rgba(59, 130, 246, 0.8)',   // Blue
            'delivered' => 'rgba(34, 197, 94, 0.8)',     // Green
            'cancelled' => 'rgba(239, 68, 68, 0.8)'      // Red
        ];

        $labels = [];
        $data = [];
        $backgroundColor = [];

        foreach ($statusLabels as $status => $label) {
            $count = $statusCounts[$status] ?? 0;
            if ($count > 0) { // Only show statuses that have data
                $labels[] = $label;
                $data[] = $count;
                $backgroundColor[] = $statusColors[$status];
            }
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => array_map(function ($color) {
                        return str_replace('0.8', '1', $color);
                    }, $backgroundColor),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
