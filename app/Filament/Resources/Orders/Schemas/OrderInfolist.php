<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Información General')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('order_number')
                                ->label('Número de Orden')
                                ->copyable(),

                            TextEntry::make('customer.user.name')
                                ->label('Cliente'),
                        ]),

                    Grid::make(2)
                        ->schema([
                            TextEntry::make('status')
                                ->label('Estado')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    Order::STATUS_PENDING => 'warning',
                                    Order::STATUS_PROCESSING => 'info',
                                    Order::STATUS_SHIPPED => 'success',
                                    Order::STATUS_DELIVERED => 'success',
                                    Order::STATUS_CANCELLED => 'danger',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    Order::STATUS_PENDING => 'Pendiente',
                                    Order::STATUS_PROCESSING => 'Procesando',
                                    Order::STATUS_SHIPPED => 'Enviado',
                                    Order::STATUS_DELIVERED => 'Entregado',
                                    Order::STATUS_CANCELLED => 'Cancelado',
                                    default => $state,
                                }),

                            TextEntry::make('payment_status')
                                ->label('Estado de Pago')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    Order::PAYMENT_STATUS_PENDING => 'warning',
                                    Order::PAYMENT_STATUS_PAID => 'success',
                                    Order::PAYMENT_STATUS_FAILED => 'danger',
                                    Order::PAYMENT_STATUS_REFUNDED => 'gray',
                                    default => 'gray',
                                })
                                ->formatStateUsing(fn (string $state): string => match ($state) {
                                    Order::PAYMENT_STATUS_PENDING => 'Pendiente',
                                    Order::PAYMENT_STATUS_PAID => 'Pagado',
                                    Order::PAYMENT_STATUS_FAILED => 'Fallido',
                                    Order::PAYMENT_STATUS_REFUNDED => 'Reembolsado',
                                    default => $state,
                                }),
                        ]),
                ]),

            Section::make('Detalles Financieros')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('subtotal')
                                ->label('Subtotal')
                                ->money('CLP'),

                            TextEntry::make('shipping_cost')
                                ->label('Costo de Envío')
                                ->money('CLP'),

                            TextEntry::make('discount_amount')
                                ->label('Descuento')
                                ->money('CLP'),
                        ]),

                    TextEntry::make('total_amount')
                        ->label('Total')
                        ->money('CLP')
                        ->size('lg')
                        ->weight('bold'),
                ]),

            Section::make('Información Adicional')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('payment_method')
                                ->label('Método de Pago'),

                            TextEntry::make('currency')
                                ->label('Moneda'),
                        ]),

                    Grid::make(2)
                        ->schema([
                            TextEntry::make('shipped_at')
                                ->label('Fecha de Envío')
                                ->dateTime('d/m/Y H:i'),

                            TextEntry::make('delivered_at')
                                ->label('Fecha de Entrega')
                                ->dateTime('d/m/Y H:i'),
                        ]),

                    TextEntry::make('notes')
                        ->label('Notas')
                        ->columnSpanFull(),
                ]),

            Section::make('Fechas')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Fecha de Creación')
                                ->dateTime('d/m/Y H:i'),

                            TextEntry::make('updated_at')
                                ->label('Última Actualización')
                                ->dateTime('d/m/Y H:i'),
                        ]),
                ])
                ->collapsible(),
        ]);
    }
}
