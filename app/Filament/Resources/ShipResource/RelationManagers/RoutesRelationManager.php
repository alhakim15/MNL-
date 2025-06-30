<?php

namespace App\Filament\Resources\ShipResource\RelationManagers;

use App\Models\City;
use App\Models\ShipRoute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoutesRelationManager extends RelationManager
{
    protected static string $relationship = 'routes';

    protected static ?string $title = 'Ship Routes';

    protected static ?string $modelLabel = 'Route';

    protected static ?string $pluralModelLabel = 'Routes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Route Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('origin_city_id')
                                    ->label('Origin City')
                                    ->options(City::orderBy('name')->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Clear destination if same as origin
                                        $destination = $set('destination_city_id', null);
                                    }),

                                Forms\Components\Select::make('destination_city_id')
                                    ->label('Destination City')
                                    ->options(function (callable $get) {
                                        $originCityId = $get('origin_city_id');
                                        return City::when($originCityId, function ($query) use ($originCityId) {
                                            return $query->where('id', '!=', $originCityId);
                                        })->orderBy('name')->pluck('name', 'id');
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('distance_km')
                                    ->label('Distance')
                                    ->numeric()
                                    ->suffix('km')
                                    ->placeholder('150')
                                    ->minValue(1)
                                    ->maxValue(10000)
                                    ->step(0.1)
                                    ->helperText('Distance between cities in kilometers'),

                                Forms\Components\TextInput::make('estimated_hours')
                                    ->label('Travel Time')
                                    ->numeric()
                                    ->suffix('hours')
                                    ->placeholder('24')
                                    ->minValue(1)
                                    ->maxValue(168)
                                    ->helperText('Estimated travel time in hours'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Route')
                                    ->default(true)
                                    ->helperText('Toggle to enable/disable this route'),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('route_description')
            ->columns([
                Tables\Columns\TextColumn::make('originCity.name')
                    ->label('From')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\IconColumn::make('arrow')
                    ->label('')
                    ->icon('heroicon-m-arrow-right')
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('destinationCity.name')
                    ->label('To')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('distance_km')
                    ->label('Distance')
                    ->suffix(' km')
                    ->sortable()
                    ->alignEnd()
                    ->color('info'),

                Tables\Columns\TextColumn::make('estimated_hours')
                    ->label('Travel Time')
                    ->suffix(' hours')
                    ->sortable()
                    ->alignEnd()
                    ->color('warning'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('origin_city_id')
                    ->label('Origin City')
                    ->options(City::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('destination_city_id')
                    ->label('Destination City')
                    ->options(City::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active routes only')
                    ->falseLabel('Inactive routes only')
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Add New Route')
                    ->modalSubmitActionLabel('Create Route')
                    ->successNotificationTitle('Route created successfully'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Route')
                    ->modalSubmitActionLabel('Update Route')
                    ->successNotificationTitle('Route updated successfully'),

                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Delete Route')
                    ->modalDescription('Are you sure you want to delete this route? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it')
                    ->successNotificationTitle('Route deleted successfully'),

                Tables\Actions\Action::make('toggle_status')
                    ->label(fn(ShipRoute $record): string => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn(ShipRoute $record): string => $record->is_active ? 'heroicon-o-pause' : 'heroicon-o-play')
                    ->color(fn(ShipRoute $record): string => $record->is_active ? 'warning' : 'success')
                    ->action(function (ShipRoute $record): void {
                        $record->update(['is_active' => !$record->is_active]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn(ShipRoute $record): string => ($record->is_active ? 'Deactivate' : 'Activate') . ' Route')
                    ->modalDescription(
                        fn(ShipRoute $record): string =>
                        'Are you sure you want to ' . ($record->is_active ? 'deactivate' : 'activate') . ' this route?'
                    )
                    ->successNotificationTitle(
                        fn(ShipRoute $record): string =>
                        'Route ' . ($record->is_active ? 'activated' : 'deactivated') . ' successfully'
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Routes')
                        ->modalDescription('Are you sure you want to delete the selected routes? This action cannot be undone.')
                        ->modalSubmitActionLabel('Yes, delete them'),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-play')
                        ->color('success')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => true]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Activate Selected Routes')
                        ->modalDescription('Are you sure you want to activate the selected routes?')
                        ->modalSubmitActionLabel('Yes, activate them')
                        ->successNotificationTitle('Selected routes activated successfully'),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->action(function ($records): void {
                            $records->each->update(['is_active' => false]);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Deactivate Selected Routes')
                        ->modalDescription('Are you sure you want to deactivate the selected routes?')
                        ->modalSubmitActionLabel('Yes, deactivate them')
                        ->successNotificationTitle('Selected routes deactivated successfully'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }
}
