<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Details')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),

                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('📦 SHIPPING ADDRESS')
                    ->schema([
                        Forms\Components\Placeholder::make('shipping_info')
                            ->label('')
                            ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                '<div style="font-size: 16px; line-height: 1.6;">' .
                                '<strong style="font-size: 18px;">' . e($record->customer_first_name . ' ' . $record->customer_last_name) . '</strong><br>' .
                                e($record->billing_address_line1) . '<br>' .
                                ($record->billing_address_line2 ? e($record->billing_address_line2) . '<br>' : '') .
                                e($record->billing_postal_code) . ' ' . e($record->billing_city) . '<br>' .
                                e($record->billing_country) . '<br><br>' .
                                '<strong>Email:</strong> ' . e($record->customer_email) . '<br>' .
                                ($record->customer_phone ? '<strong>Phone:</strong> ' . e($record->customer_phone) : '') .
                                '</div>'
                            )),
                    ])
                    ->collapsible(false),

                Forms\Components\Textarea::make('admin_notes')
                    ->label('Notes')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('customer_email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Account')
                    ->default('Guest')
                    ->searchable()
                    ->url(fn ($record) => $record->user_id ? route('filament.admin.resources.users.edit', $record->user_id) : null),

                Tables\Columns\TextColumn::make('customer_first_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($record) => $record->customer_first_name . ' ' . $record->customer_last_name),

                Tables\Columns\TextColumn::make('total')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'info' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'secondary' => 'refunded',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('payment_status'),
            ])
            ->actions([
                Tables\Actions\Action::make('printLabel')
                    ->label('Print Label')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn ($record) => route('orders.print-label', $record))
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => in_array($record->status, ['processing', 'shipped'])),

                Tables\Actions\Action::make('markShipped')
                    ->label('Ship')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->action(fn ($record) => $record->update(['status' => 'shipped']))
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'processing'),

                Tables\Actions\Action::make('markDelivered')
                    ->label('Delivered')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn ($record) => $record->update(['status' => 'delivered']))
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'shipped'),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('printLabels')
                    ->label('Print Shipping Labels')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->action(function ($records, $livewire) {
                        $orderIds = $records->pluck('id')->join(',');
                        $url = route('orders.print-labels-bulk', ['ids' => $orderIds]);

                        // Open in new tab using JavaScript
                        $livewire->js("window.open('$url', '_blank')");
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
