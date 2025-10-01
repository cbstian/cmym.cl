<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Mail\OrderConfirmationMail;
use App\Mail\TransferPaymentMail;
use App\Models\Order;
use App\Settings\EcommerceSettings;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Número de Orden')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('customer.user.name')
                    ->label('Cliente')
                    ->searchable(['customer.user.name', 'customer.user.email'])
                    ->sortable()
                    ->description(fn (Order $record): string => $record->customer?->user?->email ?? ''),

                TextColumn::make('status')
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

                TextColumn::make('payment_status')
                    ->label('Estado Pago')
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

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('CLP')
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('payment_method')
                    ->label('Método de Pago')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'transfer' => 'Transferencia',
                        'webpay' => 'WebPay',
                        default => $state,
                    })
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Fecha Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->description(fn (Order $record): string => $record->created_at->format('d/m/Y H:i')),

                TextColumn::make('shipped_at')
                    ->label('Fecha Envío')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('delivered_at')
                    ->label('Fecha Entrega')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        Order::STATUS_PENDING => 'Pendiente',
                        Order::STATUS_PROCESSING => 'Procesando',
                        Order::STATUS_SHIPPED => 'Enviado',
                        Order::STATUS_DELIVERED => 'Entregado',
                        Order::STATUS_CANCELLED => 'Cancelado',
                    ]),

                SelectFilter::make('payment_status')
                    ->label('Estado de Pago')
                    ->options([
                        Order::PAYMENT_STATUS_PENDING => 'Pendiente',
                        Order::PAYMENT_STATUS_PAID => 'Pagado',
                        Order::PAYMENT_STATUS_FAILED => 'Fallido',
                        Order::PAYMENT_STATUS_REFUNDED => 'Reembolsado',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('resend_webpay_confirmation')
                    ->label('Reenviar Confirmación')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('success')
                    ->visible(fn (Order $record): bool => $record->payment_method === 'webpay' && $record->payment_status === Order::PAYMENT_STATUS_PAID)
                    ->requiresConfirmation()
                    ->modalHeading('Reenviar Confirmación de Pedido')
                    ->modalDescription(fn (Order $record): string => "¿Deseas reenviar el correo de confirmación del pedido #{$record->order_number} al cliente {$record->customer?->user?->email}?")
                    ->modalSubmitActionLabel('Reenviar Correo')
                    ->action(function (Order $record) {
                        try {
                            Mail::to($record->customer->user->email)
                                ->send(new OrderConfirmationMail($record));

                            Notification::make()
                                ->title('Correo enviado exitosamente')
                                ->body("Se ha reenviado la confirmación del pedido #{$record->order_number}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al enviar correo')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('resend_transfer_instructions')
                    ->label('Reenviar Instrucciones')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('warning')
                    ->visible(fn (Order $record): bool => $record->payment_method === 'transfer' and $record->payment_status === Order::PAYMENT_STATUS_PENDING)
                    ->requiresConfirmation()
                    ->modalHeading('Reenviar Instrucciones de Transferencia')
                    ->modalDescription(fn (Order $record): string => "¿Deseas reenviar las instrucciones de pago por transferencia del pedido #{$record->order_number} al cliente {$record->customer?->user?->email}?")
                    ->modalSubmitActionLabel('Reenviar Correo')
                    ->action(function (Order $record) {
                        try {
                            $settings = app(EcommerceSettings::class);

                            Mail::to($record->customer->user->email)
                                ->send(new TransferPaymentMail(
                                    order: $record,
                                    bankDetails: $settings->bank_details,
                                    emailConfirmationPayment: $settings->email_confirmation_payment,
                                    notificationEmails: $settings->emails_notifications_orders
                                ));

                            Notification::make()
                                ->title('Correo enviado exitosamente')
                                ->body("Se han reenviado las instrucciones de pago del pedido #{$record->order_number}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al enviar correo')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}
