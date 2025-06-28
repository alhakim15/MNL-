<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Support\Enums\FontWeight;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('force_update_status')
                ->label('Update Status dari Midtrans')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    return redirect()->route('payment.force-update', $this->record->resi);
                })
                ->visible(fn() => $this->record->payment_status === 'pending'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Pengiriman')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('resi')
                                    ->label('Nomor Resi')
                                    ->weight(FontWeight::Bold)
                                    ->copyable(),

                                TextEntry::make('created_at')
                                    ->label('Tanggal Dibuat')
                                    ->dateTime('d F Y, H:i'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('sender_name')
                                    ->label('Pengirim'),

                                TextEntry::make('receiver_name')
                                    ->label('Penerima'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                TextEntry::make('fromCity.name')
                                    ->label('Kota Asal'),

                                TextEntry::make('toCity.name')
                                    ->label('Kota Tujuan'),

                                TextEntry::make('ship.name')
                                    ->label('Kapal'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('item_name')
                                    ->label('Nama Barang'),

                                TextEntry::make('weight')
                                    ->label('Berat')
                                    ->suffix(' ton'),
                            ]),
                    ]),

                Section::make('Informasi Pembayaran')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('shipping_cost')
                                    ->label('Biaya Pengiriman')
                                    ->money('IDR')
                                    ->weight(FontWeight::Bold),

                                TextEntry::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'failed' => 'danger',
                                        'cancelled' => 'secondary',
                                        default => 'gray',
                                    }),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('payment_type')
                                    ->label('Metode Pembayaran')
                                    ->placeholder('Belum ada'),

                                TextEntry::make('paid_at')
                                    ->label('Tanggal Pembayaran')
                                    ->dateTime('d F Y, H:i')
                                    ->placeholder('Belum dibayar'),
                            ]),

                        TextEntry::make('payment_token')
                            ->label('Token Pembayaran')
                            ->placeholder('Belum ada')
                            ->copyable()
                            ->visible(fn() => !empty($this->record->payment_token)),
                    ]),

                Section::make('Detail Teknis')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('delivery_date')
                                    ->label('Tanggal Pengiriman')
                                    ->date('d F Y'),

                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diupdate')
                                    ->dateTime('d F Y, H:i'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
