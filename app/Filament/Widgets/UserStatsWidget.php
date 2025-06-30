<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = User::where('role', 'user')->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Admin Users', $adminUsers)
                ->description('System administrators')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('danger'),

            Stat::make('Regular Users', $regularUsers)
                ->description('Standard users')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make('New This Month', $newUsersThisMonth)
                ->description('Registered in ' . now()->format('F Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
