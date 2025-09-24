<?php

namespace App\Filament\Resources\FormContacts\Pages;

use App\Filament\Resources\FormContacts\FormContactResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormContact extends CreateRecord
{
    protected static string $resource = FormContactResource::class;

    protected static ?string $title = 'Crear Contacto';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
