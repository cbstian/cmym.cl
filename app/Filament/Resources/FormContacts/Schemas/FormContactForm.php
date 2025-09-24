<?php

namespace App\Filament\Resources\FormContacts\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FormContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Contacto')
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('correo')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('direccion')
                            ->label('Dirección')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Mensaje')
                    ->schema([
                        Textarea::make('mensaje')
                            ->label('Mensaje')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Estado de Revisión')
                    ->schema([
                        Toggle::make('reviewed')
                            ->label('Revisado')
                            ->helperText('Marcar si este contacto ya ha sido revisado'),
                    ]),
            ]);
    }
}
