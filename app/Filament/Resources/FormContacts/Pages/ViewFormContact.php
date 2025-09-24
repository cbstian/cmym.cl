<?php

namespace App\Filament\Resources\FormContacts\Pages;

use App\Filament\Resources\FormContacts\FormContactResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFormContact extends ViewRecord
{
    protected static string $resource = FormContactResource::class;

    protected static ?string $title = 'Ver Contacto';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
