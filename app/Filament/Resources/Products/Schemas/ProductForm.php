<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
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

                        Section::make('Precios e Inventario')
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
                                TextInput::make('stock_quantity')
                                    ->label('Stock disponible')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->suffix('unidades')
                                    ->helperText('Cantidad de unidades disponibles en inventario'),
                            ])
                            ->columns(3),

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

                        Section::make('Atributos')
                            ->schema([
                                Repeater::make('attributes')
                                    ->relationship()
                                    ->label('Listado de atributos')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nombre')
                                            ->required()
                                            ->columnSpan(6)
                                            ->maxLength(255),
                                        Toggle::make('is_required')
                                            ->label('Requerido')
                                            ->inline(false)
                                            ->columnSpan(6)
                                            ->default(false),
                                        TagsInput::make('values')
                                            ->label('Valores')
                                            ->columnSpan(12)
                                            ->helperText('Ingrese los valores posibles para este atributo (ej: colores, tallas, etc.)')
                                            ->placeholder('Presione Enter para agregar un valor'),
                                    ])
                                    ->columns(12)
                                    ->defaultItems(0)
                                    ->addAction(
                                        fn (Action $action) => $action->label('Agregar atributo')
                                    )
                                    ->deleteAction(
                                        fn (Action $action) => $action->label('Eliminar')
                                    )
                                    ->reorderAction(
                                        fn (Action $action) => $action->label('Reordenar')
                                    )
                                    ->collapsible()
                                    ->orderColumn('sort')
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                            ])
                            ->columnSpanFull()
                            ->collapsible(),
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
