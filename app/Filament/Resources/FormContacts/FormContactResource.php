<?php

namespace App\Filament\Resources\FormContacts;

use App\Filament\Resources\FormContacts\Pages\CreateFormContact;
use App\Filament\Resources\FormContacts\Pages\EditFormContact;
use App\Filament\Resources\FormContacts\Pages\ListFormContacts;
use App\Filament\Resources\FormContacts\Pages\ViewFormContact;
use App\Filament\Resources\FormContacts\Schemas\FormContactForm;
use App\Filament\Resources\FormContacts\Schemas\FormContactInfolist;
use App\Filament\Resources\FormContacts\Tables\FormContactsTable;
use App\Models\FormContact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FormContactResource extends Resource
{
    protected static ?string $model = FormContact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Contactos';

    protected static ?string $modelLabel = 'Contacto';

    protected static ?string $pluralModelLabel = 'Contactos';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return FormContactForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormContactsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FormContactInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFormContacts::route('/'),
            'create' => CreateFormContact::route('/create'),
            'view' => ViewFormContact::route('/{record}'),
            'edit' => EditFormContact::route('/{record}/edit'),
        ];
    }
}
