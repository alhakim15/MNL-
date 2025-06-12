<?php

namespace App\Filament\Widgets;

use App\Models\City;
use App\Models\Ship;
use App\Models\Delivery;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;

class DeliveryStats extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Pengiriman', Delivery::count())
                ->description('Jumlah semua pengiriman')
                ->descriptionIcon('heroicon-m-truck'),

            Card::make('Total Berat', Delivery::sum('weight') . ' ton')
                ->description('Berat keseluruhan barang')
                ->descriptionIcon('heroicon-m-scale'),

            Card::make('Jumlah Kapal', Ship::count())
                ->description('Total kapal tersedia')
                ->descriptionIcon('heroicon-o-cube'),

            Card::make('Jumlah Kota', City::count())
                ->description('Jumlah kota asal & tujuan')
                ->descriptionIcon('heroicon-m-map'),
        ];
    }
}
