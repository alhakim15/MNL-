<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->action(fn () => $this->redirect(request()->url())),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(fn () => $this->getModel()::whereNotNull('shipping_cost')->count()),
            
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'pending'))
                ->badge(fn () => $this->getModel()::where('payment_status', 'pending')->count())
                ->badgeColor('warning'),
            
            'paid' => Tab::make('Lunas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'paid'))
                ->badge(fn () => $this->getModel()::where('payment_status', 'paid')->count())
                ->badgeColor('success'),
            
            'failed' => Tab::make('Gagal')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'failed'))
                ->badge(fn () => $this->getModel()::where('payment_status', 'failed')->count())
                ->badgeColor('danger'),
                
            'cancelled' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'cancelled'))
                ->badge(fn () => $this->getModel()::where('payment_status', 'cancelled')->count())
                ->badgeColor('secondary'),
        ];
    }
}
