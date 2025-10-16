# Configuración de Costos de Envío

Este documento explica cómo funciona el sistema de costos de envío implementado en CMYM.CL.

## Resumen

El sistema distingue entre dos tipos de envío:

1. **Región Metropolitana (RM)**: Costo fijo por comuna, configurable
2. **Otras Regiones**: Costo "por pagar" al recibir el paquete, cliente elige empresa courier

## Flujo de Compra

### Para clientes de la Región Metropolitana

1. El cliente selecciona la región "Metropolitana de Santiago"
2. Selecciona su comuna
3. El sistema calcula automáticamente el costo de envío según la comuna
4. El costo se suma al total del pedido
5. El cliente completa el pago incluyendo el envío

### Para clientes de otras regiones

1. El cliente selecciona su región (diferente a RM)
2. Selecciona su comuna
3. El sistema muestra "Envío por pagar"
4. El cliente debe seleccionar una empresa courier de su preferencia
5. El costo de envío se calcula y paga al momento de recibir el paquete
6. El cliente paga solo el valor de los productos

## Administración (Panel Filament)

### Acceso a la Configuración

1. Inicia sesión en el panel administrativo de Filament
2. Ve a **Configuración** → **Opciones**
3. Desplázate hasta las secciones de envío

### Configurar Costos de Envío para RM

**Sección**: "Costos de Envío - Región Metropolitana"

- **Agregar Comuna**: Click en "Agregar Comuna"
- **Seleccionar Comuna**: Elige la comuna del listado
- **Definir Costo**: Ingresa el costo en pesos chilenos (CLP)
- **Valor por defecto**: $10.000 CLP

**Ejemplo**:
```
Comuna: Santiago - Costo: $10.000
Comuna: Las Condes - Costo: $12.000
Comuna: Puente Alto - Costo: $15.000
```

**Notas**:
- Cada comuna solo puede aparecer una vez
- El sistema previene duplicados
- Si una comuna no está configurada, usa $10.000 por defecto

### Configurar Empresas Courier

**Sección**: "Empresas Courier"

- **Agregar Empresa**: Click en "Agregar Empresa Courier"
- **Nombre**: Ingresa el nombre exacto de la empresa

**Empresas por defecto**:
- Starken
- Chilexpress
- Correos de Chile
- Blue Express

**Notas**:
- Los clientes verán estas opciones cuando seleccionen una región fuera de RM
- Puedes agregar, eliminar o modificar las empresas según necesites

## Información Técnica

### Archivos Modificados

1. **Settings**
   - `app/Settings/EcommerceSettings.php`: Nuevos campos `shipping_costs_rm` y `courier_companies`
   - `database/settings/2025_10_15_000001_add_shipping_settings.php`: Migración con valores iniciales

2. **Base de Datos**
   - `database/migrations/2025_10_15_175708_add_courier_company_to_orders_table.php`: Campo para guardar courier seleccionado

3. **Modelos**
   - `app/Models/Order.php`: Campo `courier_company` en `$fillable`

4. **Componentes Livewire**
   - `app/Livewire/Checkout.php`: Lógica de cálculo de costos y validación
   - `resources/views/livewire/checkout.blade.php`: UI actualizada

5. **Servicios**
   - `app/Services/CheckoutService.php`: Limpieza de sesión actualizada

6. **Panel Filament**
   - `app/Filament/Pages/Options.php`: Formularios de administración

### Lógica de Cálculo

```php
// Región Metropolitana
if ($region->abbreviation === 'RM') {
    $cost = $settings->shipping_costs_rm[$commune_id] ?? 10000;
    return $cost; // Costo fijo
}

// Otras regiones
return 0; // "Por pagar" - se muestra $0 en checkout
```

### Validación

- **RM**: No requiere courier_company
- **Otras regiones**: Requiere courier_company (validación Laravel)

## Casos de Uso

### Caso 1: Cliente de Santiago

```
Región: Metropolitana de Santiago
Comuna: Santiago
Costo de envío: $10.000 (según configuración)
Total: Productos + $10.000
Pago: Total completo
```

### Caso 2: Cliente de Valparaíso

```
Región: Valparaíso
Comuna: Valparaíso
Courier: Starken (seleccionado por cliente)
Costo de envío: Por pagar
Total: Solo productos
Pago: Solo productos
Nota en orden: Envío por pagar vía Starken
```

## Testing

Se han creado tests automatizados en `tests/Feature/ShippingCostCalculationTest.php`:

- ✅ Calcula costo correcto para RM por comuna
- ✅ Muestra "por pagar" para otras regiones
- ✅ Requiere courier para regiones no-RM
- ✅ No requiere courier para RM

**Ejecutar tests**:
```bash
php artisan test --filter=ShippingCost
```

## Preguntas Frecuentes

### ¿Puedo cambiar el costo por defecto de $10.000?

Sí, en el panel de Filament puedes configurar el costo de cada comuna individualmente. Si una comuna no está configurada, usará $10.000 por defecto.

### ¿Cómo sé qué courier eligió el cliente?

En el panel de Filament, al ver una orden, verás el campo "Courier Company" con la empresa seleccionada por el cliente.

### ¿Puedo agregar más empresas courier?

Sí, en **Configuración** → **Opciones** → **Empresas Courier**, puedes agregar tantas como necesites.

### ¿Qué pasa si el cliente cambia de región durante el checkout?

El sistema actualiza automáticamente:
- Si cambia de RM a otra región: muestra selector de courier
- Si cambia de otra región a RM: oculta selector de courier y calcula costo

### ¿Cómo cambio el mensaje "Por pagar"?

El mensaje está en la vista `resources/views/livewire/checkout.blade.php`. Busca:
```blade
<span class="total-value text-info">
    <small>Por pagar</small>
</span>
```

## Soporte

Para cualquier problema o pregunta sobre esta funcionalidad, revisa:
1. Los tests en `tests/Feature/ShippingCostCalculationTest.php`
2. El componente Livewire en `app/Livewire/Checkout.php`
3. La documentación de Spatie Settings

---

**Última actualización**: 15 de octubre de 2025
