<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipResource\Pages;
use App\Filament\Resources\ShipResource\RelationManagers;
use App\Models\Ship;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShipResource extends Resource
{
    protected static ?string $model = Ship::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Fleet Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Ship Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Nama Kapal')
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('max_weight')
                        ->numeric()
                        ->required()
                        ->suffix('ton')
                        ->placeholder('1000')
                        ->helperText('Berat maksimum kapal (dalam ton).')
                        ->minValue(1)
                        ->maxValue(10000),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ship Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('max_weight')
                    ->label('Max Capacity')
                    ->suffix(' ton')
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('current_load')
                    ->label('Current Load')
                    ->getStateUsing(fn($record) => $record->deliveries()->sum('weight'))
                    ->suffix(' ton')
                    ->badge()
                    ->color(function ($record) {
                        $currentWeight = $record->deliveries()->sum('weight');
                        $percentage = $record->max_weight > 0 ? ($currentWeight / $record->max_weight) * 100 : 0;
                        return $percentage > 80 ? 'danger' : ($percentage > 60 ? 'warning' : 'success');
                    })
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('load_percentage')
                    ->label('Load %')
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
                    })
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('routes_count')
                    ->label('Routes')
                    ->counts(['routes' => fn(Builder $query) => $query->where('is_active', true)])
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('active_routes')
                    ->label('Available Routes')
                    ->getStateUsing(function ($record) {
                        $routes = $record->routes()
                            ->with(['originCity', 'destinationCity'])
                            ->where('is_active', true)
                            ->get();

                        if ($routes->isEmpty()) {
                            return 'No routes assigned';
                        }

                        return $routes
                            ->map(fn($route) => $route->originCity->name . ' → ' . $route->destinationCity->name)
                            ->join(', ');
                    })
                    ->wrap()
                    ->limit(100)
                    ->tooltip(function ($record) {
                        $routes = $record->routes()
                            ->with(['originCity', 'destinationCity'])
                            ->where('is_active', true)
                            ->get();

                        return $routes
                            ->map(fn($route) => $route->originCity->name . ' → ' . $route->destinationCity->name . ' (' . $route->distance_km . ' km, ' . $route->estimated_hours . ' hours)')
                            ->join("\n");
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('has_routes')
                    ->label('Route Status')
                    ->options([
                        'with_routes' => 'Has Routes',
                        'without_routes' => 'No Routes',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] === 'with_routes',
                            fn(Builder $query) => $query->has('routes')
                        )->when(
                            $data['value'] === 'without_routes',
                            fn(Builder $query) => $query->doesntHave('routes')
                        );
                    }),

                Tables\Filters\SelectFilter::make('origin_city')
                    ->label('Origin City')
                    ->options(City::orderBy('name')->pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->whereHas('routes', function ($query) use ($value) {
                                $query->where('origin_city_id', $value)->where('is_active', true);
                            })
                        );
                    }),

                Tables\Filters\SelectFilter::make('destination_city')
                    ->label('Destination City')
                    ->options(City::orderBy('name')->pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->whereHas('routes', function ($query) use ($value) {
                                $query->where('destination_city_id', $value)->where('is_active', true);
                            })
                        );
                    }),

                Tables\Filters\Filter::make('overloaded')
                    ->label('Overloaded Ships')
                    ->query(
                        fn(Builder $query): Builder =>
                        $query->whereHas('deliveries', function ($query) {
                            $query->selectRaw('SUM(weight) as total_weight')
                                ->groupBy('ship_id')
                                ->havingRaw('SUM(weight) > ships.max_weight');
                        })
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Ship')
                    ->modalDescription('Are you sure you want to delete this ship? This will also delete all associated routes.')
                    ->modalSubmitActionLabel('Yes, delete it'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Ships')
                        ->modalDescription('Are you sure you want to delete the selected ships? This will also delete all associated routes.'),
                ]),
            ])
            ->defaultSort('name')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RoutesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShips::route('/'),
            'create' => Pages\CreateShip::route('/create'),
            'view' => Pages\ViewShip::route('/{record}'),
            'edit' => Pages\EditShip::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
