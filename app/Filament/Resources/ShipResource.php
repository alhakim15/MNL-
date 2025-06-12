<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShipResource\Pages;
use App\Filament\Resources\ShipResource\RelationManagers;
use App\Models\Ship;
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

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('max_weight')
                ->numeric()
                ->required()
                ->suffix('ton')
                ->helperText('Berat maksimum kapal (dalam ton).'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('max_weight')->label('Max Weight (Ton)'),
                Tables\Columns\TextColumn::make('deliveries_sum_weight')
                    ->label('Total Weight')
                    ->getStateUsing(fn($record) => $record->deliveries()->sum('weight') . ' ton'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListShips::route('/'),
            'create' => Pages\CreateShip::route('/create'),
            'edit' => Pages\EditShip::route('/{record}/edit'),
        ];
    }
}
