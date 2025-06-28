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
        // Total pembayaran
        $totalPayments = Delivery::whereNotNull('shipping_cost')->count();
        $totalRevenue = Delivery::where('payment_status', 'paid')->sum('shipping_cost');
        
        // Pembayaran hari ini
        $todayPayments = Delivery::whereDate('created_at', today())
            ->whereNotNull('shipping_cost')
            ->count();
        $todayRevenue = Delivery::whereDate('paid_at', today())
            ->where('payment_status', 'paid')
            ->sum('shipping_cost');

        // Pembayaran pending
        $pendingPayments = Delivery::where('payment_status', 'pending')->count();
        $pendingValue = Delivery::where('payment_status', 'pending')->sum('shipping_cost');

        // Success rate
        $successRate = $totalPayments > 0 
            ? round((Delivery::where('payment_status', 'paid')->count() / $totalPayments) * 100, 1)
            : 0;

        return [
            Stat::make('Total Pembayaran', number_format($totalPayments))
                ->description('Semua transaksi pembayaran')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue))
                ->description('Pembayaran yang sudah lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pembayaran Hari Ini', number_format($todayPayments))
                ->description('Rp ' . number_format($todayRevenue) . ' terkumpul')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Pembayaran Pending', number_format($pendingPayments))
                ->description('Rp ' . number_format($pendingValue) . ' menunggu')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Success Rate', $successRate . '%')
                ->description('Tingkat keberhasilan pembayaran')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger')),
        ];
    }
}
