<?php

namespace App\Filament\Resources\ShipResource\Pages;

use App\Filament\Resources\ShipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewShip extends ViewRecord
{
    protected static string $resource = ShipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Ship Information')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Ship Name')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('max_weight')
                                    ->label('Maximum Capacity')
                                    ->suffix(' ton')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('success'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Current Load Information')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('current_load')
                                    ->label('Current Load')
                                    ->getStateUsing(fn($record) => $record->deliveries()->sum('weight'))
                                    ->suffix(' ton')
                                    ->badge()
                                    ->color(function ($record) {
                                        $currentWeight = $record->deliveries()->sum('weight');
                                        $percentage = $record->max_weight > 0 ? ($currentWeight / $record->max_weight) * 100 : 0;
                                        return $percentage > 80 ? 'danger' : ($percentage > 60 ? 'warning' : 'success');
                                    }),

                                Infolists\Components\TextEntry::make('remaining_capacity')
                                    ->label('Remaining Capacity')
                                    ->getStateUsing(function ($record) {
                                        $currentWeight = $record->deliveries()->sum('weight');
                                        return max(0, $record->max_weight - $currentWeight);
                                    })
                                    ->suffix(' ton')
                                    ->badge()
                                    ->color('info'),

                                Infolists\Components\TextEntry::make('load_percentage')
                                    ->label('Load Percentage')
                                    ->getStateUsing(function ($record) {
                                        $currentWeight = $record->deliveries()->sum('weight');
                                        $percentage = $record->max_weight > 0 ? ($currentWeight / $record->max_weight) * 100 : 0;
                                        return number_format($percentage, 1) . '%';
                                    })
                                    ->badge()
                                    ->color(function ($record) {
                                        $currentWeight = $record->deliveries()->sum('weight');
                                        $percentage = $record->max_weight > 0 ? ($currentWeight / $record->max_weight) * 100 : 0;
                                        return $percentage > 80 ? 'danger' : ($percentage > 60 ? 'warning' : 'success');
                                    }),
                            ]),
                    ]),

                Infolists\Components\Section::make('Route Summary')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_routes')
                                    ->label('Total Routes')
                                    ->getStateUsing(fn($record) => $record->routes()->count())
                                    ->badge()
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('active_routes')
                                    ->label('Active Routes')
                                    ->getStateUsing(fn($record) => $record->routes()->where('is_active', true)->count())
                                    ->badge()
                                    ->color('success'),
                            ]),

                        Infolists\Components\TextEntry::make('route_list')
                            ->label('Available Routes')
                            ->getStateUsing(function ($record) {
                                $routes = $record->routes()
                                    ->with(['originCity', 'destinationCity'])
                                    ->where('is_active', true)
                                    ->get();

                                if ($routes->isEmpty()) {
                                    return 'No active routes assigned';
                                }

                                return $routes
                                    ->map(function ($route) {
                                        return sprintf(
                                            '%s → %s (%s km, %s hours)',
                                            $route->originCity->name,
                                            $route->destinationCity->name,
                                            $route->distance_km ?? 'N/A',
                                            $route->estimated_hours ?? 'N/A'
                                        );
                                    })
                                    ->join("\n");
                            })
                            ->columnSpanFull()
                            ->listWithLineBreaks(),
                    ]),

                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->dateTime(),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
