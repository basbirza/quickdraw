<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Product Images';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->directory('products')
                    ->required()
                    ->imageEditor()
                    ->maxSize(5120),

                Forms\Components\Select::make('image_type')
                    ->options([
                        'main' => 'Main Image',
                        'gallery' => 'Gallery Image',
                        'thumbnail' => 'Thumbnail',
                    ])
                    ->default('gallery')
                    ->required(),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first'),

                Forms\Components\TextInput::make('alt_text')
                    ->label('Alt Text (for SEO)')
                    ->placeholder('Description of the image'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('image_path')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->square(),

                Tables\Columns\BadgeColumn::make('image_type')
                    ->colors([
                        'primary' => 'main',
                        'secondary' => 'gallery',
                        'warning' => 'thumbnail',
                    ]),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('alt_text')
                    ->limit(40),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('image_type')
                    ->options([
                        'main' => 'Main',
                        'gallery' => 'Gallery',
                        'thumbnail' => 'Thumbnail',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Upload Image'),
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }
}
