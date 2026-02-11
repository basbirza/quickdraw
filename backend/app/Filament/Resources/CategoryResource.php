<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->options([
                        'main' => 'Main Category',
                        'sub' => 'Subcategory',
                    ])
                    ->default('main')
                    ->required(),

                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->placeholder('No parent (main category)'),

                Forms\Components\Textarea::make('description')
                    ->rows(3),

                Forms\Components\FileUpload::make('banner_image')
                    ->label('Category Tile Image')
                    ->image()
                    ->directory('categories')
                    ->imageEditor()
                    ->maxSize(5120)
                    ->helperText('Background image for category tile on homepage (optional)'),

                Forms\Components\Placeholder::make('tile_preview')
                    ->label('Tile Preview (How it will look on homepage)')
                    ->content(fn ($record) => $record && $record->banner_image
                        ? new \Illuminate\Support\HtmlString('
                            <div style="position: relative; aspect-ratio: 4/3; background-color: #2c3e6b; background-image: url(' . asset('storage/' . $record->banner_image) . '); background-size: cover; background-position: center; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; padding: 20px;">
                                <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.3);"></div>
                                <span style="position: relative; z-index: 10; font-size: 16px; font-weight: 600; letter-spacing: 0.1em;">' . strtoupper($record->name) . '</span>
                                <span style="position: relative; z-index: 10; font-size: 12px; margin-top: 8px; opacity: 0.8;">' . $record->products()->count() . ' Products</span>
                            </div>
                        ')
                        : 'Upload an image to see preview'),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Order on homepage (lower = first)'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'main',
                        'secondary' => 'sub',
                    ]),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->default('—'),

                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'main' => 'Main',
                        'sub' => 'Subcategory',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
