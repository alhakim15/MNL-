<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Delivery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class PaymentResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('resi')
                    ->label('Nomor Resi')
                    ->disabled(),
                
                Forms\Components\TextInput::make('sender_name')
                    ->label('Nama Pengirim')
                    ->disabled(),

                Forms\Components\TextInput::make('receiver_name')
                    ->label('Nama Penerima')
                    ->disabled(),

                Forms\Components\TextInput::make('item_name')
                    ->label('Nama Barang')
                    ->disabled(),

                Forms\Components\TextInput::make('weight')
                    ->label('Berat (Ton)')
                    ->disabled()
                    ->suffix('ton'),

                Forms\Components\TextInput::make('shipping_cost')
                    ->label('Biaya Pengiriman')
                    ->disabled()
                    ->prefix('Rp ')
                    ->numeric(),

                Forms\Components\Select::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Lunas',
                        'failed' => 'Gagal',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('payment_type')
                    ->label('Metode Pembayaran')
                    ->disabled(),

                Forms\Components\DateTimePicker::make('paid_at')
                    ->label('Tanggal Pembayaran')
                    ->disabled(),

                Forms\Components\DateTimePicker::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('resi')
                    ->label('Nomor Resi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('sender_name')
                    ->label('Pengirim')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('receiver_name')
                    ->label('Penerima')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fromCity.name')
                    ->label('Asal')
                    ->searchable(),

                TextColumn::make('toCity.name')
                    ->label('Tujuan')
                    ->searchable(),

                TextColumn::make('shipping_cost')
                    ->label('Biaya')
                    ->money('IDR')
                    ->sortable(),

                BadgeColumn::make('payment_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'secondary' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'paid',
                        'heroicon-o-x-circle' => 'failed',
                        'heroicon-o-minus-circle' => 'cancelled',
                    ]),

                TextColumn::make('payment_type')
                    ->label('Metode')
                    ->placeholder('Belum ada'),

                TextColumn::make('paid_at')
                    ->label('Tgl Bayar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Belum dibayar'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Lunas',
                        'failed' => 'Gagal',
                        'cancelled' => 'Dibatalkan',
                    ]),

                SelectFilter::make('payment_type')
                    ->label('Metode Pembayaran')
                    ->options([
                        'credit_card' => 'Kartu Kredit',
                        'bank_transfer' => 'Transfer Bank',
                        'echannel' => 'E-Channel',
                        'gopay' => 'GoPay',
                        'qris' => 'QRIS',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                Action::make('update_status')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function (Delivery $record) {
                        try {
                            // Panggil Midtrans API untuk cek status
                            $midtransService = app(\App\Services\MidtransService::class);
                            
                            // Simulasi update status - dalam implementasi nyata, 
                            // Anda bisa memanggil Midtrans API di sini
                            Log::info('Admin requested payment status update for: ' . $record->resi);
                            
                            // Redirect ke payment controller untuk force update
                            return redirect()->route('payment.force-update', $record->resi);
                            
                        } catch (\Exception $e) {
                            Log::error('Failed to update payment status: ' . $e->getMessage());
                            
                            return redirect()->back()->with('error', 'Gagal mengupdate status pembayaran');
                        }
                    })
                    ->visible(fn (Delivery $record): bool => $record->payment_status === 'pending'),

                Action::make('mark_as_paid')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai sebagai Lunas')
                    ->modalDescription('Apakah Anda yakin ingin menandai pembayaran ini sebagai lunas?')
                    ->action(function (Delivery $record) {
                        $record->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                            'payment_type' => 'manual_admin'
                        ]);
                        
                        Log::info('Admin manually marked payment as paid for: ' . $record->resi);
                    })
                    ->visible(fn (Delivery $record): bool => $record->payment_status === 'pending'),

                Action::make('cancel_payment')
                    ->label('Batalkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Batalkan Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin membatalkan pembayaran ini?')
                    ->action(function (Delivery $record) {
                        $record->update([
                            'payment_status' => 'cancelled'
                        ]);
                        
                        Log::info('Admin cancelled payment for: ' . $record->resi);
                    })
                    ->visible(fn (Delivery $record): bool => in_array($record->payment_status, ['pending', 'failed'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('mark_paid')
                        ->label('Tandai Lunas')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->payment_status === 'pending') {
                                    $record->update([
                                        'payment_status' => 'paid',
                                        'paid_at' => now(),
                                        'payment_type' => 'manual_admin_bulk'
                                    ]);
                                }
                            });
                            
                            Log::info('Admin bulk marked payments as paid');
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['fromCity', 'toCity', 'ship'])
            ->whereNotNull('shipping_cost'); // Only show deliveries with payment info
    }
}
