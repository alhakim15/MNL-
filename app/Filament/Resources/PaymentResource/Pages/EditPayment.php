<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            
            Actions\Action::make('reset_payment')
                ->label('Reset Pembayaran')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reset Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin mereset pembayaran ini ke status pending?')
                ->action(function () {
                    $this->record->update([
                        'payment_status' => 'pending',
                        'payment_type' => null,
                        'paid_at' => null,
                    ]);
                    
                    Log::info('Admin reset payment status for: ' . $this->record->resi);
                    
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                })
                ->visible(fn () => in_array($this->record->payment_status, ['paid', 'failed', 'cancelled'])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function afterSave(): void
    {
        Log::info('Payment record updated by admin', [
            'resi' => $this->record->resi,
            'old_status' => $this->record->getOriginal('payment_status'),
            'new_status' => $this->record->payment_status,
        ]);
    }
}
