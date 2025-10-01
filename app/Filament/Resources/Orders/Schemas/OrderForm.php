<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Información General')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('order_number')
                                ->label('Número de Orden')
                                ->disabled()
                                ->dehydrated()
                                ->placeholder('Se generará automáticamente'),

                            Select::make('customer_id')
                                ->label('Cliente')
                                ->relationship('customer')
                                ->getOptionLabelFromRecordUsing(fn (Customer $record): string => "{$record->user->name} ({$record->user->email})")
                                ->searchable(['user.name', 'user.email'])
                                ->preload()
                                ->required(),
                        ]),

                    Grid::make(2)
                        ->schema([
                            Select::make('status')
                                ->label('Estado')
                                ->options([
                                    Order::STATUS_PENDING => 'Pendiente',
                                    Order::STATUS_PROCESSING => 'Procesando',
                                    Order::STATUS_SHIPPED => 'Enviado',
                                    Order::STATUS_DELIVERED => 'Entregado',
                                    Order::STATUS_CANCELLED => 'Cancelado',
                                ])
                                ->default(Order::STATUS_PENDING)
                                ->required(),

                            Select::make('payment_status')
                                ->label('Estado de Pago')
                                ->options([
                                    Order::PAYMENT_STATUS_PENDING => 'Pendiente',
                                    Order::PAYMENT_STATUS_PAID => 'Pagado',
                                    Order::PAYMENT_STATUS_FAILED => 'Fallido',
                                    Order::PAYMENT_STATUS_REFUNDED => 'Reembolsado',
                                ])
                                ->default(Order::PAYMENT_STATUS_PENDING)
                                ->required(),
                        ]),
                ]),

            Section::make('Detalles Financieros')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->prefix('$')
                                ->suffix('CLP')
                                ->required(),

                            TextInput::make('shipping_cost')
                                ->label('Costo de Envío')
                                ->numeric()
                                ->prefix('$')
                                ->suffix('CLP')
                                ->default(0),

                            TextInput::make('discount_amount')
                                ->label('Descuento')
                                ->numeric()
                                ->prefix('$')
                                ->suffix('CLP')
                                ->default(0),
                        ]),

                    TextInput::make('total_amount')
                        ->label('Total')
                        ->numeric()
                        ->prefix('$')
                        ->suffix('CLP')
                        ->required(),

                    Hidden::make('currency')
                        ->default('CLP'),
                ]),

            Section::make('Información Adicional')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Select::make('payment_method')
                                ->label('Método de Pago')
                                ->options([
                                    Payment::METHOD_WEBPAY => 'Webpay',
                                    Payment::METHOD_TRANSFER => 'Transferencia',
                                    Payment::METHOD_CASH => 'Efectivo',
                                ])
                                ->required(),
                        ]),

                    Grid::make(2)
                        ->schema([
                            DateTimePicker::make('shipped_at')
                                ->label('Fecha de Envío'),

                            DateTimePicker::make('delivered_at')
                                ->label('Fecha de Entrega'),
                        ]),

                    Textarea::make('notes')
                        ->label('Notas')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Direcciones')
                ->schema([
                    Grid::make(1)
                        ->schema([
                            Select::make('billingAddress')
                                ->label('Dirección de Facturación')
                                ->relationship(titleAttribute: 'full_address')
                                ->getOptionLabelFromRecordUsing(fn (Address $record) => "{$record->full_address}")
                                ->disabled()
                                ->required(),

                            Select::make('shippingAddress')
                                ->label('Dirección de Envío')
                                ->relationship(titleAttribute: 'full_address')
                                ->getOptionLabelFromRecordUsing(fn (Address $record) => "{$record->full_address}")
                                ->disabled()
                                ->required(),
                        ]),
                ]),
        ]);
    }
}
