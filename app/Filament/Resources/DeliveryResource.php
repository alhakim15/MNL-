<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Delivery;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\DeliveryStatusLog;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DeliveryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DeliveryResource\RelationManagers;
use App\Filament\Resources\DeliveryStatusLogsResource\RelationManagers\StatusLogsRelationManager;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('sender_name')->required(),
                TextInput::make('receiver_name')->required(),
                Select::make('from_city_id')
                    ->label('From City')
                    ->relationship('fromCity', 'name')
                    ->searchable()
                    ->required(),
                Select::make('to_city_id')
                    ->label('To City')
                    ->relationship('toCity', 'name')
                    ->searchable()
                    ->required(),
                DatePicker::make('delivery_date')->required(),
                TextInput::make('item_name')->required(),
                TextInput::make('weight')->numeric()->required(),
                Select::make('ship_id')
                    ->label('Ship')
                    ->relationship('ship', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('resi')
                    ->label('Resi')
                    ->maxLength(50)
                    ->helperText('Optional, can be left empty if not available'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('sender_name')->searchable(),
                TextColumn::make('receiver_name')->searchable(),
                TextColumn::make('fromCity.name')->label('From'),
                TextColumn::make('toCity.name')->label('To'),
                TextColumn::make('delivery_date')->date(),
                TextColumn::make('item_name')->searchable(),
                TextColumn::make('weight')->suffix(' ton'),
                TextColumn::make('ship.name')->label('Ship'),
                TextColumn::make('resi')->label('Resi')
                    ->getStateUsing(fn($record) => $record->resi ?: 'Not Available'),
                TextColumn::make('latestStatus.status')->label('Latest Status')
                    ->getStateUsing(fn($record) => $record->latestStatus ? $record->latestStatus->status : 'No Status Yet')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('from_city_id')
                    ->label('From City')
                    ->relationship('fromCity', 'name'),
                Tables\Filters\SelectFilter::make('to_city_id')
                    ->label('To City')
                    ->relationship('toCity', 'name'),
                Tables\Filters\SelectFilter::make('ship_id')
                    ->label('Ship')
                    ->relationship('ship', 'name'),
                Tables\Filters\SelectFilter::make('latestStatus.status')
                    ->label('Latest Status')
                    ->options(DeliveryStatusLog::STATUS)
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('latestStatus', function (Builder $q) use ($data) {
                                $q->where('status', $data['value']);
                            });
                        }
                    })
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('update_status')
                    ->label('Ubah Status')
                    ->icon('heroicon-m-pencil-square')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status Baru')
                            ->options(DeliveryStatusLog::STATUS)
                            ->required(),

                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi')
                            ->required(),

                        Forms\Components\Textarea::make('note')
                            ->label('Catatan')
                            ->rows(2),

                        Forms\Components\DateTimePicker::make('logged_at')
                            ->label('Waktu')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (Delivery $record, array $data) {
                        $record->statusLogs()->create($data);
                    })
                    ->modalHeading('Update Status Pengiriman')
                    ->color('primary')
                    ->visible(function (Delivery $record) {
                        $lastStatus = $record->latestStatus?->status;
                        return !in_array($lastStatus, [
                            DeliveryStatusLog::STATUS['DELIVERED'],
                            DeliveryStatusLog::STATUS['CANCELLED'],
                        ]);
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function relations(): array
    {
        return [
            StatusLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}
