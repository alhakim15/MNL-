<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Delivery;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DeliveryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DeliveryResource\RelationManagers;

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender_name')->searchable(),
                TextColumn::make('receiver_name')->searchable(),
                TextColumn::make('fromCity.name')->label('From'),
                TextColumn::make('toCity.name')->label('To'),
                TextColumn::make('delivery_date')->date(),
                TextColumn::make('item_name')->searchable(),
                TextColumn::make('weight')->suffix(' ton'),
                TextColumn::make('ship.name')->label('Ship'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('from_city_id')
                    ->label('From City')
                    ->relationship('fromCity', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
