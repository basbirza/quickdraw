<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Setting Name')
                    ->disabled(),

                Forms\Components\Textarea::make('description')
                    ->disabled()
                    ->rows(2),

                Forms\Components\TextInput::make('value')
                    ->label('Value')
                    ->required()
                    ->helperText('Edit the value for this setting'),

                Forms\Components\TextInput::make('key')
                    ->disabled()
                    ->helperText('Setting identifier (read-only)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('value')
                    ->searchable()
                    ->limit(60)
                    ->copyable(),

                Tables\Columns\BadgeColumn::make('group')
                    ->colors([
                        'primary' => 'store',
                        'success' => 'homepage',
                        'warning' => 'shipping',
                        'info' => 'social',
                        'danger' => 'marketing',
                    ]),

                Tables\Columns\TextColumn::make('type')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'store' => 'Store Info',
                        'homepage' => 'Homepage',
                        'shipping' => 'Shipping',
                        'social' => 'Social Media',
                        'marketing' => 'Marketing',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultGroup('group')
            ->defaultSort('group');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
