# Dashboard de Ventas - Filament 4

## Descripción General

El dashboard de ventas proporciona una vista completa de las métricas y detalles de ventas del sistema e-commerce CMYM. Está construido con Filament 4 y se muestra en la página principal del panel de administración.

## Widgets Implementados

### 1. SalesOverviewWidget (Widget de Resumen de Ventas)
**Tipo:** Stats Overview Widget
**Orden:** 1

Muestra 4 métricas clave en tarjetas:

- **Ventas del Mes**: Total de ventas del mes actual en CLP, con comparación porcentual vs. mes anterior y mini gráfico de tendencia de los últimos 7 días
- **Órdenes del Mes**: Cantidad total de órdenes recibidas en el mes actual
- **Órdenes Pendientes**: Número de órdenes que requieren atención (estado pendiente)
- **Ticket Promedio**: Valor promedio de las órdenes pagadas

**Características:**
- Iconos dinámicos que cambian según el rendimiento
- Colores adaptativos (verde para positivo, rojo para negativo)
- Mini gráficos de tendencia integrados

### 2. SalesChartWidget (Gráfico de Ventas)
**Tipo:** Chart Widget (Línea)
**Orden:** 2
**Ancho:** Full

Muestra un gráfico de líneas con las ventas diarias.

**Filtros Disponibles:**
- Últimos 7 días
- Últimos 30 días (predeterminado)
- Últimos 90 días

**Características:**
- Gráfico de línea con área rellena
- Color verde corporativo (#44AD49)
- Suma de ventas pagadas por día

### 3. RecentOrdersWidget (Órdenes Recientes)
**Tipo:** Table Widget
**Orden:** 3
**Ancho:** Full

Tabla con las 10 órdenes más recientes del sistema.

**Columnas:**
- Número de Orden (searchable, sortable)
- Cliente (nombre del usuario asociado)
- Total (formateado como CLP)
- Estado (badge con colores: warning/info/success/danger)
- Estado de Pago (badge con colores)
- Fecha de creación

**Características:**
- Carga relaciones (customer.user, items) para optimizar consultas
- Badges con colores semánticos
- Ordenamiento por defecto: más recientes primero

### 4. TopProductsWidget (Productos Más Vendidos)
**Tipo:** Table Widget
**Orden:** 4
**Ancho:** Full

Tabla con los 10 productos más vendidos según cantidad de unidades.

**Columnas:**
- Imagen del producto (60x60px)
- Nombre del producto
- SKU
- Precio
- Unidades Vendidas (badge verde)
- Ingresos Generados (suma de total_price de order_items)

**Características:**
- Ordenamiento por cantidad vendida (usando FIELD en SQL)
- Cálculo de ingresos totales por producto
- Imagen placeholder si no hay imagen

### 5. PaymentMethodsWidget (Ventas por Método de Pago)
**Tipo:** Chart Widget (Doughnut/Donut)
**Orden:** 5

Gráfico circular que muestra la distribución de ventas por método de pago.

**Métodos Soportados:**
- WebPay Plus (verde #44AD49)
- Transferencia (azul #3B82F6)
- Efectivo (naranja #F59E0B)

**Características:**
- Solo considera órdenes pagadas
- Colores personalizados por método
- Suma total de ventas por método

### 6. OrderStatusWidget (Órdenes por Estado)
**Tipo:** Chart Widget (Bar/Barras)
**Orden:** 6

Gráfico de barras que muestra la cantidad de órdenes por estado.

**Estados:**
- Pendiente (naranja)
- Procesando (azul)
- Enviado (púrpura)
- Entregado (verde)
- Cancelado (rojo)

**Características:**
- Conteo de órdenes por estado
- Colores semánticos por estado
- Gráfico de barras horizontales

## Acceso al Dashboard

El dashboard está disponible en:
```
/admin
```

**Requisitos:**
- Usuario autenticado en el panel de administración
- Permisos de acceso al panel admin

## Estructura de Archivos

```
app/Filament/Widgets/
├── SalesOverviewWidget.php      # Resumen de métricas
├── SalesChartWidget.php          # Gráfico de ventas diarias
├── RecentOrdersWidget.php        # Tabla de órdenes recientes
├── TopProductsWidget.php         # Tabla de productos top
├── PaymentMethodsWidget.php      # Gráfico de métodos de pago
└── OrderStatusWidget.php         # Gráfico de estados de orden
```

## Configuración

Los widgets se auto-descubren gracias a la configuración en `AdminPanelProvider.php`:

```php
->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
```

El orden de visualización se controla mediante la propiedad `$sort` en cada widget.

## Consideraciones de Rendimiento

1. **Eager Loading**: Los widgets cargan relaciones necesarias para evitar N+1 queries
2. **Límites**: Las tablas tienen límites (10 registros) para mantener el dashboard ágil
3. **Índices**: Asegurarse de que las tablas orders, order_items y payments tengan índices apropiados
4. **Caché**: Considerar implementar caché para widgets con cálculos pesados en producción

## Personalización

### Cambiar Colores
Los colores están definidos en cada widget y pueden modificarse:
- Color primario del dashboard: `#44AD49` (verde CMYM)
- Colores de badges y gráficos: definidos en arrays `$colorMap`

### Ajustar Períodos
Los filtros de tiempo están en `SalesChartWidget`:
```php
protected function getFilters(): ?array
{
    return [
        '7' => 'Últimos 7 días',
        '30' => 'Últimos 30 días',
        '90' => 'Últimos 90 días',
    ];
}
```

### Modificar Límites de Tablas
En widgets de tabla, cambiar el método `limit()`:
```php
->query(
    Order::query()
        ->latest()
        ->limit(10)  // Cambiar este número
)
```

## Monitoreo y Mantenimiento

- Verificar logs de errores si los widgets no cargan
- Revisar que las relaciones de modelos estén correctamente definidas
- Asegurar que los estados y métodos de pago coincidan con los valores en la BD
- Ejecutar `php artisan filament:cache-components` después de cambios

## Próximas Mejoras Sugeridas

1. Implementar caché para queries pesadas
2. Agregar exportación de datos a Excel/CSV
3. Incluir filtros de rango de fechas personalizados
4. Agregar widget de productos con bajo stock
5. Implementar comparación de períodos (semana vs semana, mes vs mes)
6. Agregar métricas de clientes (nuevos, recurrentes)
7. Incluir análisis de regiones con más ventas
