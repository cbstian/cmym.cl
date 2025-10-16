<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $topProductIds = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->pluck('total_quantity', 'product_id');

        $query = Product::query();

        if ($topProductIds->isNotEmpty()) {
            $query->whereIn('id', $topProductIds->keys())
                ->orderByRaw('FIELD(id, '.implode(',', $topProductIds->keys()->toArray()).')');
        } else {
            // Si no hay productos vendidos, retornar query vacía
            $query->whereRaw('0 = 1');
        }

        return $table
            ->heading('Productos Más Vendidos')
            ->query($query)
            ->columns([
                ImageColumn::make('image_primary_path')
                    ->label('Imagen')
                    ->square()
                    ->size(60)
                    ->defaultImageUrl(url('/images/placeholder.png')),

                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Precio')
                    ->money('CLP')
                    ->sortable(),

                TextColumn::make('total_sold')
                    ->label('Unidades Vendidas')
                    ->getStateUsing(function (Product $record) use ($topProductIds) {
                        return $topProductIds[$record->id] ?? 0;
                    })
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('revenue')
                    ->label('Ingresos Generados')
                    ->getStateUsing(function (Product $record) {
                        $revenue = OrderItem::where('product_id', $record->id)
                            ->sum('total_price');

                        return $revenue;
                    })
                    ->money('CLP')
                    ->sortable(),
            ]);
    }
}
