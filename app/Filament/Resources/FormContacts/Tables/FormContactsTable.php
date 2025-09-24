<?php

namespace App\Filament\Resources\FormContacts\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FormContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('correo')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable()
                    ->placeholder('No especificado')
                    ->copyable(),
                TextColumn::make('direccion')
                    ->label('Dirección')
                    ->searchable()
                    ->placeholder('No especificada')
                    ->limit(30),
                TextColumn::make('mensaje')
                    ->label('Mensaje')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= 50) {
                            return null;
                        }

                        return $state;
                    }),
                IconColumn::make('reviewed')
                    ->label('Revisado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha de Contacto')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('reviewed')
                    ->label('Estado de Revisión')
                    ->options([
                        true => 'Revisados',
                        false => 'Sin revisar',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (! isset($data['value'])) {
                            return $query;
                        }

                        return $query->where('reviewed', $data['value']);
                    }),
            ])
            ->recordActions([
                Action::make('toggle_reviewed')
                    ->label(fn ($record) => $record->reviewed ? 'Marcar sin revisar' : 'Marcar como revisado')
                    ->icon(fn ($record) => $record->reviewed ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->reviewed ? 'danger' : 'success')
                    ->action(function ($record) {
                        $record->update(['reviewed' => ! $record->reviewed]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => $record->reviewed ? 'Marcar como sin revisar' : 'Marcar como revisado')
                    ->modalDescription(fn ($record) => $record->reviewed
                        ? '¿Estás seguro de que quieres marcar este contacto como sin revisar?'
                        : '¿Estás seguro de que quieres marcar este contacto como revisado?'),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
