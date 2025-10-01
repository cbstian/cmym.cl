<?php

namespace App\Filament\Pages;

use App\Settings\EcommerceSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Options extends SettingsPage
{
    protected static string $settings = EcommerceSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Opciones';

    protected static ?string $title = 'Opciones del Ecommerce';

    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Notificaciones')
                    ->description('Configuración de emails para notificaciones del sistema')
                    ->schema([
                        Repeater::make('emails_notifications_orders')
                            ->label('Emails de Notificación de Órdenes')
                            ->helperText('Lista de emails que recibirán notificaciones cuando se generen nuevas órdenes')
                            ->simple(
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('admin@cmym.cl')
                            )
                            ->reorderable()
                            ->cloneable()
                            ->collapsible()
                            ->minItems(1)
                            ->columns(1),
                    ]),

                Section::make('Información Bancaria')
                    ->description('Detalles de la cuenta bancaria para transferencias')
                    ->schema([
                        Textarea::make('bank_details')
                            ->label('Detalles Bancarios')
                            ->helperText('Información que se mostrará a los usuarios cuando seleccionen el método de pago por transferencia')
                            ->required()
                            ->rows(6)
                            ->placeholder("Banco de Chile\nCuenta Corriente: 12345678-9\nRUT: 12.345.678-9\nTitular: CMYM SpA\nEmail de confirmación: pagos@cmym.cl")
                            ->columnSpanFull(),
                    ]),

                Section::make('Email de Confirmación de Pago')
                    ->description('Email al que se enviará la confirmación de pago cuando un usuario realice una transferencia bancaria')
                    ->schema([
                        TextInput::make('email_confirmation_payment')
                            ->label('Email de Confirmación')
                            ->helperText('Email que recibirá la confirmación de pago por transferencia bancaria')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('ejemplo@cmym.cl')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
