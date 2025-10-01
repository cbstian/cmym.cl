# Configuración del Ecommerce - Spatie Settings

Este proyecto utiliza [Filament Spatie Settings](https://filamentphp.com/plugins/filament-spatie-settings) para gestionar las opciones del ecommerce de forma centralizada.

## Opciones Disponibles

### 1. Emails de Notificación de Órdenes
- **Campo:** `emails_notifications_orders`
- **Tipo:** Array de emails
- **Descripción:** Lista de emails que recibirán notificaciones cuando se generen nuevas órdenes

### 2. Detalles Bancarios
- **Campo:** `bank_details`
- **Tipo:** String (textarea)
- **Descripción:** Información de la cuenta bancaria para transferencias que se mostrará a los usuarios

## Acceso desde el Panel Administrativo

1. Inicia sesión en el panel administrativo de Filament
2. Ve a **Configuración** → **Opciones**
3. Edita los campos según sea necesario
4. Guarda los cambios

## Uso Programático

### Acceso Directo a Settings

```php
use App\Settings\EcommerceSettings;

// Obtener instancia de settings
$settings = app(EcommerceSettings::class);

// Obtener emails de notificación
$emails = $settings->emails_notifications_orders;

// Obtener detalles bancarios
$bankDetails = $settings->bank_details;
```

### Usando Métodos Helper

```php
use App\Models\Order;
use App\Models\Payment;

// Obtener emails de notificación desde Order
$notificationEmails = Order::getNotificationEmails();

// Obtener detalles bancarios desde Payment
$bankDetails = Payment::getBankDetails();
```

## Ejemplos de Uso

### Envío de Notificaciones de Órdenes

```php
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

// Al crear una nueva orden
$order = Order::create([...]);

// Enviar notificación a todos los emails configurados
$notificationEmails = Order::getNotificationEmails();

foreach ($notificationEmails as $email) {
    Mail::to($email)->send(new OrderNotification($order));
}
```

### Mostrar Información Bancaria en Checkout

```php
use App\Models\Payment;

// En el controlador de checkout o vista
$bankDetails = Payment::getBankDetails();

return view('checkout.transfer', [
    'bankDetails' => $bankDetails
]);
```

### En una Vista Blade

```blade
@if($paymentMethod === 'transfer')
    <div class="bank-details">
        <h3>Información para Transferencia</h3>
        <pre>{!! nl2br(e(\App\Models\Payment::getBankDetails())) !!}</pre>
    </div>
@endif
```

## Estructura de la Base de Datos

Los settings se almacenan en la tabla `settings` con la siguiente estructura:

```sql
-- Emails de notificación (almacenado como JSON)
INSERT INTO settings (group, name, payload) VALUES 
('ecommerce', 'emails_notifications_orders', '["admin@cmym.cl","ventas@cmym.cl"]');

-- Detalles bancarios (almacenado como string)
INSERT INTO settings (group, name, payload) VALUES 
('ecommerce', 'bank_details', '"Banco de Chile\nCuenta Corriente: 12345678-9\nRUT: 12.345.678-9\nTitular: CMYM SpA"');
```

## Tests

Los settings incluyen tests completos que verifican:

- Carga correcta de configuraciones
- Valores por defecto
- Actualización de configuraciones
- Métodos helper en modelos

Ejecutar tests:

```bash
php artisan test tests/Feature/EcommerceSettingsTest.php
```
