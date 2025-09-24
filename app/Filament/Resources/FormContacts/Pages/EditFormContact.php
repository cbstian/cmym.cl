<?php

namespace App\Filament\Resources\FormContacts\Pages;

use App\Filament\Resources\FormContacts\FormContactResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFormContact extends EditRecord
{
    protected static string $resource = FormContactResource::class;

    protected static ?string $title = 'Editar Contacto';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
