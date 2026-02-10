<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(200)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', \Illuminate\Support\Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Textarea::make('short_description')
                                    ->required()
                                    ->maxLength(200)
                                    ->rows(2),

                                Forms\Components\RichEditor::make('full_description')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('€')
                                    ->maxValue(99999.99),

                                Forms\Components\Select::make('tag')
                                    ->options([
                                        'NEW' => 'NEW',
                                        'BESTSELLER' => 'BESTSELLER',
                                    ])
                                    ->placeholder('No tag'),

                                Forms\Components\ColorPicker::make('color_hex')
                                    ->label('Color'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Fabric Specifications')
                            ->schema([
                                Forms\Components\TextInput::make('weight')
                                    ->placeholder('14oz'),
                                Forms\Components\TextInput::make('mill')
                                    ->placeholder('Kaihara, Japan'),
                                Forms\Components\TextInput::make('composition')
                                    ->placeholder('100% Cotton'),
                                Forms\Components\TextInput::make('construction')
                                    ->placeholder('Right-hand twill'),
                                Forms\Components\TextInput::make('treatment')
                                    ->placeholder('Raw / Unwashed'),
                                Forms\Components\TextInput::make('sanforization')
                                    ->placeholder('Unsanforized'),
                            ])
                            ->columns(2)
                            ->collapsible(),

                        Forms\Components\Section::make('Additional Information')
                            ->schema([
                                Forms\Components\Textarea::make('sizing_info')
                                    ->rows(3),
                                Forms\Components\Textarea::make('shipping_info')
                                    ->rows(2),
                                Forms\Components\Textarea::make('care_instructions')
                                    ->rows(3),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured'),
                                Forms\Components\TextInput::make('stock_quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                            ]),

                        Forms\Components\Section::make('Sizes')
                            ->schema([
                                Forms\Components\TagsInput::make('sizes_available')
                                    ->placeholder('Add sizes')
                                    ->helperText('Press Enter after each size'),
                            ]),

                        Forms\Components\Section::make('Categories')
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->multiple()
                                    ->relationship('categories', 'name')
                                    ->preload(),
                            ]),

                        Forms\Components\Section::make('Product Images')
                            ->schema([
                                Forms\Components\FileUpload::make('product_images')
                                    ->label('Upload Images')
                                    ->multiple()
                                    ->image()
                                    ->directory('products')
                                    ->maxSize(5120)
                                    ->imageEditor()
                                    ->helperText('Upload product images (max 5MB each). First image will be the main image.')
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        // Store image paths for saving
                                        $set('uploaded_images', $state);
                                    }),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image_url')
                    ->label('Image')
                    ->square()
                    ->defaultImageUrl('/images/placeholder.png'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('short_description')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tag')
                    ->colors([
                        'success' => 'NEW',
                        'warning' => 'BESTSELLER',
                    ]),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('categories.name')
                    ->badge()
                    ->separator(','),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tag')
                    ->options([
                        'NEW' => 'NEW',
                        'BESTSELLER' => 'BESTSELLER',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
