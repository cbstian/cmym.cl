<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'product_name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('product_id')
                    ->label('Producto')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (?int $state, Set $set, Get $get): void {
                        if (! $state) {
                            return;
                        }

                        $product = Product::find($state);

                        if (! $product) {
                            return;
                        }

                        // Completar automáticamente los campos del producto
                        $set('product_name', $product->name);
                        $set('product_sku', $product->sku);
                        $set('product_description', $product->short_description ?? $product->description);
                        $set('product_image_path', $product->image_primary_path);

                        // Establecer el precio (usar sale_price si existe, sino price)
                        $unitPrice = $product->sale_price ?? $product->price;
                        $set('unit_price', $unitPrice);

                        // Calcular el precio total basado en la cantidad actual
                        $quantity = $get('quantity') ?? 1;
                        $set('total_price', $unitPrice * $quantity);
                    }),

                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1)
                    ->live()
                    ->afterStateUpdated(function (?int $state, Set $set, Get $get): void {
                        $unitPrice = $get('unit_price');
                        if ($unitPrice && $state) {
                            $set('total_price', $unitPrice * $state);
                        }
                    }),

                // Campos ocultos que se llenarán automáticamente
                Hidden::make('product_name'),
                Hidden::make('product_sku'),
                Hidden::make('unit_price'),
                Hidden::make('total_price'),
                Hidden::make('product_description'),
                Hidden::make('product_image_path'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                ImageColumn::make('product_image_path')
                    ->label('Imagen')
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/images/placeholder.png'),

                TextColumn::make('product_name')
                    ->label('Producto')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('product_sku')
                    ->label('SKU')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->alignCenter(),

                TextColumn::make('unit_price')
                    ->label('Precio Unitario')
                    ->money('CLP'),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('CLP')
                    ->weight('bold'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                // EditAction::make(),
                // ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
