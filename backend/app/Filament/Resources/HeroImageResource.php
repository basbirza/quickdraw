<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroImageResource\Pages;
use App\Models\HeroImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroImageResource extends Resource
{
    protected static ?string $model = HeroImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Homepage';

    protected static ?string $navigationLabel = 'Hero Slideshow';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Hero Image')
                    ->image()
                    ->directory('hero')
                    ->required()
                    ->imageEditor()
                    ->maxSize(10240)
                    ->helperText('Upload hero banner image (recommended: 1920x800px, max 10MB)'),

                Forms\Components\TextInput::make('alt_text')
                    ->label('Alt Text')
                    ->placeholder('Quickdraw Pressing Co. - Premium Selvedge Denim')
                    ->helperText('Image description for accessibility'),

                Forms\Components\Select::make('campaign')
                    ->label('Campaign')
                    ->options([
                        'default' => 'Default (Year-Round)',
                        'spring' => 'Spring Collection',
                        'summer' => 'Summer Collection',
                        'fall' => 'Fall Collection',
                        'winter' => 'Winter Collection',
                        'sale' => 'Sale / Black Friday',
                        'new-arrivals' => 'New Arrivals',
                        'holiday' => 'Holiday Season',
                    ])
                    ->searchable()
                    ->default('default')
                    ->required()
                    ->helperText('Select campaign. Change active campaign in Settings → Site Settings.'),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Lower numbers appear first in slideshow'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Show in slideshow'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Preview')
                    ->disk('public')
                    ->height(80),

                Tables\Columns\BadgeColumn::make('campaign')
                    ->colors([
                        'primary' => 'default',
                        'success' => fn ($state) => in_array($state, ['spring', 'summer']),
                        'warning' => fn ($state) => in_array($state, ['fall', 'sale']),
                        'info' => fn ($state) => in_array($state, ['winter', 'holiday']),
                        'danger' => 'new-arrivals',
                    ])
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alt_text')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('campaign')
                    ->options([
                        'default' => 'Default',
                        'spring' => 'Spring',
                        'summer' => 'Summer',
                        'fall' => 'Fall',
                        'winter' => 'Winter',
                        'sale' => 'Sale',
                        'new-arrivals' => 'New Arrivals',
                        'holiday' => 'Holiday',
                    ])
                    ->label('Filter by Campaign'),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroImages::route('/'),
            'create' => Pages\CreateHeroImage::route('/create'),
            'edit' => Pages\EditHeroImage::route('/{record}/edit'),
        ];
    }
}
