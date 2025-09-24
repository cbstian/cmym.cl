<?php

namespace App\Filament\Resources\FormContacts\Pages;

use App\Filament\Resources\FormContacts\FormContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFormContacts extends ListRecords
{
    protected static string $resource = FormContactResource::class;

    protected static ?string $title = 'Contactos';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Contacto'),
        ];
    }
}
