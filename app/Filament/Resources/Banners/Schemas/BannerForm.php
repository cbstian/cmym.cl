<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Banner')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Nombre identificador del banner (no se muestra públicamente)')
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->columnSpan(1)
                            ->helperText('Si está desactivado, el banner no se mostrará en la portada'),

                        FileUpload::make('desktop_image')
                            ->label('Imagen Desktop')
                            ->image()
                            ->required()
                            ->visibility('public')
                            ->disk('public')
                            ->directory('banners/desktop')
                            ->imageEditor()
                            ->helperText('Imagen para visualización en desktop (recomendado: 1920x600px)'),

                        FileUpload::make('mobile_image')
                            ->label('Imagen Mobile')
                            ->image()
                            ->visibility('public')
                            ->disk('public')
                            ->directory('banners/mobile')
                            ->imageEditor()
                            ->helperText('Imagen para visualización en dispositivos móviles (recomendado: 600x600px)'),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Section::make('Configuración de Enlace')
                    ->schema([
                        TextInput::make('link')
                            ->label('URL de Enlace')
                            ->url()
                            ->maxLength(255)
                            ->helperText('URL a la que redirige el banner al hacer clic')
                            ->columnSpanFull(),

                        Toggle::make('open_new_tab')
                            ->label('Abrir en nueva pestaña')
                            ->default(false)
                            ->helperText('Si está activado, el enlace se abrirá en una nueva pestaña'),
                    ])
                    ->columnSpanFull()
                    ->columns(1),
            ]);
    }
}
