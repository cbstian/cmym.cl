<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Básica')
                    ->schema([
                        TextEntry::make('category.name')
                            ->label('Categoría'),
                        TextEntry::make('name')
                            ->label('Nombre'),
                        TextEntry::make('slug'),
                        TextEntry::make('sku')
                            ->label('SKU'),
                        TextEntry::make('description')
                            ->label('Descripción')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('short_description')
                            ->label('Descripción corta')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Precios')
                    ->schema([
                        TextEntry::make('price')
                            ->label('Precio')
                            ->money('CLP'),
                        TextEntry::make('sale_price')
                            ->label('Precio de oferta')
                            ->money('CLP')
                            ->placeholder('Sin oferta'),
                    ])
                    ->columns(2),

                Section::make('Detalles Físicos')
                    ->schema([
                        TextEntry::make('weight')
                            ->label('Peso (kg)')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('dimensions')
                            ->label('Dimensiones')
                            ->placeholder('-'),
                    ])
                    ->columns(2),

                Section::make('Imágenes')
                    ->schema([
                        ImageEntry::make('image_primary_path')
                            ->label('Imagen principal'),
                        ImageEntry::make('image_paths')
                            ->label('Imágenes adicionales')
                            ->limit(5)
                            ->limitedRemainingText(),
                    ])
                    ->columns(2),

                Section::make('Estado')
                    ->schema([
                        IconEntry::make('is_active')
                            ->label('Activo')
                            ->boolean(),
                        IconEntry::make('is_featured')
                            ->label('Destacado')
                            ->boolean(),
                        TextEntry::make('created_at')
                            ->label('Creado')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Actualizado')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2),
            ]);
    }
}
