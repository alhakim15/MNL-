<?php

namespace App\Filament\Widgets;

use App\Models\Delivery;
use App\Models\Ship;
use App\Models\User;
use App\Models\DeliveryStatusLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class DeliveryStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Total deliveries
        $totalDeliveries = Delivery::count();
        $deliveriesToday = Delivery::whereDate('created_at', today())->count();
        $deliveriesThisMonth = Delivery::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate percentage change from last month
        $lastMonthDeliveries = Delivery::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $monthlyGrowth = $lastMonthDeliveries > 0
            ? round((($deliveriesThisMonth - $lastMonthDeliveries) / $lastMonthDeliveries) * 100, 1)
            : 0;

        // Active ships
        $totalShips = Ship::count();
        $activeShips = Ship::whereHas('deliveries', function ($query) {
            $query->whereHas('statusLogs', function ($statusQuery) {
                $statusQuery->where('status', DeliveryStatusLog::STATUS['IN TRANSIT']);
            });
        })->count();

        // Total weight shipped this month
        $totalWeightThisMonth = Delivery::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('weight');

        // Revenue estimation (assuming price per ton)
        $avgPricePerTon = 1000000; // 1 juta per ton (adjust as needed)
        $estimatedRevenue = $totalWeightThisMonth * $avgPricePerTon;

        // Delivery success rate
        $completedDeliveries = DeliveryStatusLog::where('status', 'delivered')
            ->whereIn('delivery_id', function ($query) {
                $query->select('id')->from('deliveries')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            })->count();

        $successRate = $deliveriesThisMonth > 0
            ? round(($completedDeliveries / $deliveriesThisMonth) * 100, 1)
            : 0;

        return [
            Stat::make('Total Pengiriman', $totalDeliveries)
                ->description($deliveriesToday . ' pengiriman hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Pengiriman Bulan Ini', $deliveriesThisMonth)
                ->description($monthlyGrowth >= 0 ? "+{$monthlyGrowth}% dari bulan lalu" : "{$monthlyGrowth}% dari bulan lalu")
                ->descriptionIcon($monthlyGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthlyGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('Kapal Aktif', $activeShips . ' / ' . $totalShips)
                ->description('Kapal dalam perjalanan')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('Total Muatan Bulan Ini', number_format($totalWeightThisMonth, 1) . ' ton')
                ->description('Estimasi pendapatan: Rp ' . number_format($estimatedRevenue / 1000000, 1) . 'M')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Tingkat Keberhasilan', $successRate . '%')
                ->description('Pengiriman berhasil bulan ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger')),

            Stat::make('Total User', User::count())
                ->description(User::whereDate('created_at', today())->count() . ' user baru hari ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
