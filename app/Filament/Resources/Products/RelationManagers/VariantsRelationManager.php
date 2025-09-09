<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\Attribute;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Variante')
                    ->schema([
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Precios y Stock')
                    ->schema([
                        TextInput::make('price')
                            ->label('Precio')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01),
                        TextInput::make('compare_price')
                            ->label('Precio de comparación')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01),
                        TextInput::make('cost_price')
                            ->label('Precio de costo')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01),
                        TextInput::make('stock_quantity')
                            ->label('Cantidad en stock')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('weight')
                            ->label('Peso (kg)')
                            ->numeric()
                            ->step(0.01),
                    ])
                    ->columns(2),

                Section::make('Atributos')
                    ->schema([
                        Repeater::make('variant_attributes')
                            ->label('Atributos de la variante')
                            ->relationship('attributeValues')
                            ->schema([
                                Select::make('attribute_id')
                                    ->label('Atributo')
                                    ->options(Attribute::pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('attribute_value_id', null)),
                                Select::make('attribute_value_id')
                                    ->label('Valor')
                                    ->options(function (callable $get) {
                                        $attributeId = $get('attribute_id');
                                        if (!$attributeId) {
                                            return [];
                                        }
                                        return Attribute::find($attributeId)?->attributeValues?->pluck('value', 'id') ?? [];
                                    })
                                    ->required(),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string =>
                                isset($state['attribute_id'], $state['attribute_value_id'])
                                    ? Attribute::find($state['attribute_id'])?->name . ': ' . \App\Models\AttributeValue::find($state['attribute_value_id'])?->value
                                    : null
                            )
                            ->collapsible(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sku')
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('attributes_summary')
                    ->label('Atributos')
                    ->getStateUsing(function ($record) {
                        return $record->attributeValues
                            ->map(fn ($value) => $value->attribute->name . ': ' . $value->value)
                            ->join(', ');
                    })
                    ->wrap(),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('CLP')
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    }),
                TextColumn::make('weight')
                    ->label('Peso')
                    ->numeric()
                    ->suffix(' kg')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('is_active')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Activo' : 'Inactivo')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
