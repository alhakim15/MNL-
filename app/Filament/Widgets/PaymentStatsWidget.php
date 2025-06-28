<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class PaymentStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Total muatan bulan ini
        $currentMonth = Carbon::now();
        $totalWeightThisMonth = Delivery::whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->sum('weight');

        // Estimasi pendapatan dari muatan bulan ini
        $estimatedRevenue = $totalWeightThisMonth * 1000000; // 1 juta per ton

        // Tingkat keberhasilan pengiriman bulan ini
        $totalDeliveriesThisMonth = Delivery::whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();

        $successfulDeliveriesThisMonth = Delivery::whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->where('payment_status', 'paid')
            ->count();

        $successRate = $totalDeliveriesThisMonth > 0
            ? round(($successfulDeliveriesThisMonth / $totalDeliveriesThisMonth) * 100, 1)
            : 0;

        return [
            Stat::make('Total Muatan Bulan Ini', number_format($totalWeightThisMonth, 1) . ' ton')
                ->description('Estimasi pendapatan: Rp ' . number_format($estimatedRevenue / 1000000, 1) . 'M')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),

            Stat::make('Tingkat Keberhasilan', $successRate . '%')
                ->description('Pengiriman berhasil bulan ini')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger')),
        ];
    }
}
