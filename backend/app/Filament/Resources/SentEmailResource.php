<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SentEmailResource\Pages;
use App\Filament\Resources\SentEmailResource\RelationManagers;
use App\Models\SentEmail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SentEmailResource extends Resource
{
    protected static ?string $model = SentEmail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationLabel = 'Sent Emails';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Email Details')
                    ->schema([
                        Forms\Components\TextInput::make('type')
                            ->disabled(),

                        Forms\Components\TextInput::make('recipient_email')
                            ->label('To')
                            ->disabled(),

                        Forms\Components\TextInput::make('recipient_name')
                            ->disabled(),

                        Forms\Components\TextInput::make('subject')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('order_id')
                            ->relationship('order', 'order_number')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'sent' => 'Sent',
                                'failed' => 'Failed',
                                'queued' => 'Queued',
                            ])
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('sent_at')
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Error Details')
                    ->schema([
                        Forms\Components\Textarea::make('error_message')
                            ->disabled()
                            ->rows(3),
                    ])
                    ->visible(fn ($record) => $record && $record->status === 'failed'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'order_confirmation',
                        'info' => 'order_shipped',
                        'warning' => 'password_reset',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('recipient_email')
                    ->searchable()
                    ->label('To'),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->searchable()
                    ->url(fn ($record) => $record->order_id ? route('filament.admin.resources.orders.edit', $record->order_id) : null),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'sent',
                        'danger' => 'failed',
                        'warning' => 'queued',
                    ]),

                Tables\Columns\TextColumn::make('sent_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type'),
                Tables\Filters\SelectFilter::make('status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('sent_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSentEmails::route('/'),
        ];
    }
}
