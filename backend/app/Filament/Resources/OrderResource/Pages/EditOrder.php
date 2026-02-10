<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('printLabel')
                ->label('Print Shipping Label')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn ($record) => route('orders.print-label', $record))
                ->openUrlInNewTab()
                ->visible(fn ($record) => in_array($record->status, ['processing', 'shipped', 'delivered'])),
            Actions\DeleteAction::make(),
        ];
    }
}
