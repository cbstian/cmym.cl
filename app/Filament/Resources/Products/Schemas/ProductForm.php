<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)
                    ->columnSpan(9)
                    ->schema([
                        Section::make('Información Básica')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Select::make('category_id')
                                    ->label('Categoría')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Textarea::make('short_description')
                                    ->label('Descripción corta')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Textarea::make('description')
                                    ->label('Descripción')
                                    ->rows(5)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Section::make('Precios')
                            ->schema([
                                TextInput::make('price')
                                    ->label('Precio')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                                TextInput::make('sale_price')
                                    ->label('Precio de oferta')
                                    ->numeric()
                                    ->prefix('$')
                                    ->step(0.01),
                            ])
                            ->columns(2),

                        Section::make('Detalles Físicos')
                            ->schema([
                                TextInput::make('weight')
                                    ->label('Peso (kg)')
                                    ->numeric()
                                    ->step(0.01),
                                TextInput::make('dimensions')
                                    ->label('Dimensiones')
                                    ->helperText('Formato: Alto x Ancho x Profundidad (cm)'),
                            ])
                            ->columns(2),

                        Section::make('Imágenes')
                            ->schema([
                                FileUpload::make('image_primary_path')
                                    ->label('Imagen principal')
                                    ->image()
                                    ->required()
                                    ->directory('products')
                                    ->visibility('public')
                                    ->maxSize(5120),
                                FileUpload::make('image_paths')
                                    ->label('Imágenes adicionales')
                                    ->image()
                                    ->multiple()
                                    ->directory('products')
                                    ->visibility('public')
                                    ->maxSize(5120)
                                    ->maxFiles(10),
                            ])
                            ->columns(1)
                            ->columnSpanFull(),
                    ]),

                Grid::make(1)
                    ->columnSpan(3)
                    ->schema([

                        Section::make('Estado')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Activo')
                                    ->default(true),
                                Toggle::make('is_featured')
                                    ->label('Destacado')
                                    ->default(false),
                            ]),
                    ]),
            ])->columns(12);
    }
}
