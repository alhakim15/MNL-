<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use Filament\Widgets\ChartWidget;

class PaymentStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Pembayaran';

    protected static string $color = 'info';

    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $payments = Delivery::whereNotNull('shipping_cost')
            ->selectRaw('payment_status, COUNT(*) as count')
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status')
            ->toArray();

        $labels = [];
        $data = [];
        $colors = [];

        $statusConfig = [
            'pending' => ['label' => 'Pending', 'color' => 'rgba(251, 191, 36, 0.8)'],
            'paid' => ['label' => 'Lunas', 'color' => 'rgba(34, 197, 94, 0.8)'],
            'failed' => ['label' => 'Gagal', 'color' => 'rgba(239, 68, 68, 0.8)'],
            'cancelled' => ['label' => 'Dibatalkan', 'color' => 'rgba(107, 114, 128, 0.8)'],
        ];

        foreach ($statusConfig as $status => $config) {
            if (isset($payments[$status]) && $payments[$status] > 0) {
                $labels[] = $config['label'];
                $data[] = $payments[$status];
                $colors[] = $config['color'];
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pembayaran',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => array_map(function ($color) {
                        return str_replace('0.8', '1', $color);
                    }, $colors),
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
