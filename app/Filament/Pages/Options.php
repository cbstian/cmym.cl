<?php

namespace App\Filament\Pages;

use App\Models\Location\Commune;
use App\Models\Location\Region;
use App\Settings\EcommerceSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Options extends SettingsPage
{
    protected static string $settings = EcommerceSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Opciones';

    protected static ?string $title = 'Opciones del Ecommerce';

    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    public function form(Schema $schema): Schema
    {
        // Obtener la Región Metropolitana y sus comunas
        $rmRegion = Region::where('abbreviation', 'RM')->first();
        $rmCommunes = [];

        if ($rmRegion) {
            $rmCommunes = Commune::whereHas('province', function ($query) use ($rmRegion) {
                $query->where('region_id', $rmRegion->id);
            })
                ->where('active', true)
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn ($commune) => [$commune->id => $commune->name])
                ->toArray();
        }

        return $schema
            ->schema([
                Section::make('Notificaciones')
                    ->description('Configuración de emails para notificaciones del sistema')
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('emails_notifications_orders')
                            ->label('Emails de Notificación de Órdenes')
                            ->helperText('Lista de emails que recibirán notificaciones cuando se generen nuevas órdenes')
                            ->simple(
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('admin@cmym.cl')
                            )
                            ->reorderable()
                            ->cloneable()
                            ->collapsible()
                            ->minItems(1)
                            ->columns(1),
                    ]),

                Section::make('Información Bancaria')
                    ->description('Detalles de la cuenta bancaria para transferencias')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Textarea::make('bank_details')
                            ->label('Detalles Bancarios')
                            ->helperText('Información que se mostrará a los usuarios cuando seleccionen el método de pago por transferencia')
                            ->required()
                            ->rows(6)
                            ->placeholder("Banco de Chile\nCuenta Corriente: 12345678-9\nRUT: 12.345.678-9\nTitular: CMYM SpA\nEmail de confirmación: pagos@cmym.cl")
                            ->columnSpanFull(),
                    ]),

                Section::make('Email de Confirmación de Pago')
                    ->description('Email al que se enviará la confirmación de pago cuando un usuario realice una transferencia bancaria')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('email_confirmation_payment')
                            ->label('Email de Confirmación')
                            ->helperText('Email que recibirá la confirmación de pago por transferencia bancaria')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('ejemplo@cmym.cl')
                            ->columnSpanFull(),
                    ]),

                Section::make('Costos de Envío - Región Metropolitana')
                    ->description('Configure los costos de envío para cada comuna de la Región Metropolitana')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Repeater::make('shipping_costs_rm')
                            ->label('Costos por Comuna')
                            ->helperText('Defina el costo de envío en pesos chilenos (CLP) para cada comuna de la RM')
                            ->table([
                                TableColumn::make('Comuna'),
                                TableColumn::make('Costo'),
                            ])
                            ->schema([
                                Select::make('commune_id')
                                    ->label('Comuna')
                                    ->options($rmCommunes)
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                                TextInput::make('cost')
                                    ->label('Costo de Envío (CLP)')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->default(10000)
                                    ->suffix('CLP'),
                            ])
                            ->columns(2)
                            ->reorderable(false)
                            ->collapsible()
                            ->deletable(false)
                            ->addable(false)
                            ->itemLabel(fn (array $state): ?string => isset($state['commune_id']) && isset($rmCommunes[$state['commune_id']])
                                ? $rmCommunes[$state['commune_id']].' - $'.number_format($state['cost'] ?? 0, 0, ',', '.')
                                : 'Nueva Comuna')
                            ->defaultItems(0),
                    ]),

                Section::make('Empresas Courier')
                    ->description('Empresas de courier disponibles para envíos fuera de la Región Metropolitana (costo "por pagar")')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Repeater::make('courier_companies')
                            ->label('Empresas Courier')
                            ->helperText('Los clientes de otras regiones podrán elegir entre estas opciones. El costo se calculará y pagará al recibir el paquete.')
                            ->simple(
                                TextInput::make('name')
                                    ->label('Nombre de la Empresa')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Starken, Chilexpress, etc.')
                            )
                            ->reorderable()
                            ->cloneable()
                            ->collapsible()
                            ->minItems(1)
                            ->columns(1)
                            ->addActionLabel('Agregar Empresa Courier')
                            ->defaultItems(0),
                    ]),
            ]);
    }

    /**
     * Transformar datos antes de guardar
     */
    /*
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Transformar shipping_costs_rm de array de objetos a array asociativo
        if (isset($data['shipping_costs_rm']) && is_array($data['shipping_costs_rm'])) {
            $transformedCosts = [];
            foreach ($data['shipping_costs_rm'] as $item) {
                if (isset($item['commune_id']) && isset($item['cost'])) {
                    $transformedCosts[$item['commune_id']] = (int) $item['cost'];
                }
            }
            $data['shipping_costs_rm'] = $transformedCosts;
        }

        // Transformar courier_companies de array de objetos a array simple de strings
        if (isset($data['courier_companies']) && is_array($data['courier_companies'])) {
            $data['courier_companies'] = array_map(
                fn ($item) => is_array($item) ? $item['name'] : $item,
                $data['courier_companies']
            );
        }

        return $data;
    }
        */

    /**
     * Transformar datos antes de llenar el formulario
     */
    /*
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Transformar shipping_costs_rm de array asociativo a array de objetos
        if (isset($data['shipping_costs_rm']) && is_array($data['shipping_costs_rm'])) {
            $transformedCosts = [];
            foreach ($data['shipping_costs_rm'] as $communeId => $cost) {
                $transformedCosts[] = [
                    'commune_id' => $communeId,
                    'cost' => $cost,
                ];
            }
            $data['shipping_costs_rm'] = $transformedCosts;
        }

        // Transformar courier_companies de array simple a array de objetos
        if (isset($data['courier_companies']) && is_array($data['courier_companies'])) {
            $data['courier_companies'] = array_map(
                fn ($name) => is_string($name) ? ['name' => $name] : $name,
                $data['courier_companies']
            );
        }

        return $data;
    }*/
}
