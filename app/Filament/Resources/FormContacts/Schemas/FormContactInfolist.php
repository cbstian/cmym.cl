<?php

namespace App\Filament\Resources\FormContacts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FormContactInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Contacto')
                    ->schema([
                        TextEntry::make('nombre')
                            ->label('Nombre')
                            ->icon('heroicon-o-user'),
                        TextEntry::make('correo')
                            ->label('Correo Electrónico')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),
                        TextEntry::make('telefono')
                            ->label('Teléfono')
                            ->icon('heroicon-o-phone')
                            ->placeholder('No especificado')
                            ->copyable(),
                        TextEntry::make('direccion')
                            ->label('Dirección')
                            ->icon('heroicon-o-map-pin')
                            ->placeholder('No especificada'),
                    ])
                    ->columns(2),

                Section::make('Mensaje')
                    ->schema([
                        TextEntry::make('mensaje')
                            ->label('Mensaje')
                            ->prose(),
                    ]),

                Section::make('Estado de Revisión')
                    ->schema([
                        IconEntry::make('reviewed')
                            ->label('Estado de Revisión')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ]),

                Section::make('Información del Sistema')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Fecha de Contacto')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i:s'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
