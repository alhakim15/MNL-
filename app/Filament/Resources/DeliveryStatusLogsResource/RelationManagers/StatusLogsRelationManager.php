<?php

namespace App\Filament\Resources\DeliveryStatusLogsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'statusLogs';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('status')
                ->label('Status')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('location')
                ->label('Lokasi / Pelabuhan')
                ->required(),

            Forms\Components\Textarea::make('note')
                ->label('Catatan Tambahan')
                ->rows(2),

            Forms\Components\DateTimePicker::make('logged_at')
                ->label('Waktu Status')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('status')->label('Status')->sortable(),
                Tables\Columns\TextColumn::make('location')->label('Lokasi'),
                Tables\Columns\TextColumn::make('note')->label('Catatan')->limit(20),
                Tables\Columns\TextColumn::make('logged_at')->label('Waktu')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
