# Modelo de Datos E-commerce Laravel 12

## 1. USUARIOS Y AUTENTICACIÓN

### User (users)
```php
id, name, email, email_verified_at, password, phone, 
birth_date, gender, created_at, updated_at
```

### Customer (customers)
```php
id, user_id, rut, company_name, created_at, updated_at
```

### Address (addresses)
```php
id, customer_id, type (billing/shipping), name, phone,
region_id, commune_id, address_line_1, address_line_2, 
postal_code, is_default, created_at, updated_at
```

## 2. GEOGRAFÍA CHILE

### Region (regions)
```php
id, name, code, created_at, updated_at
```

### Commune (communes)
```php
id, region_id, name, code, created_at, updated_at
```

## 3. CATÁLOGO DE PRODUCTOS

### Category (categories)
```php
id, parent_id, name, slug, description, image, 
is_active, sort_order, created_at, updated_at
```

### Tag (tags)
```php
id, name, slug, color, created_at, updated_at
```

### Product (products)
```php
id, category_id, name, slug, description, short_description,
sku, price, compare_price, cost_price, weight, dimensions,
meta_title, meta_description, is_active, is_featured,
created_at, updated_at
```

### ProductTag (product_tags)
```php
product_id, tag_id
```

### ProductImage (product_images)
```php
id, product_id, image_path, alt_text, is_primary, 
sort_order, created_at, updated_at
```

## 4. VARIACIONES DE PRODUCTOS

### Attribute (attributes)
```php
id, name, slug, type (color/size/model), is_required,
sort_order, created_at, updated_at
```

### AttributeValue (attribute_values)
```php
id, attribute_id, value, hex_color (nullable), 
sort_order, created_at, updated_at
```

### ProductVariant (product_variants)
```php
id, product_id, sku, price, compare_price, cost_price,
stock_quantity, weight, is_active, created_at, updated_at
```

### ProductVariantAttribute (product_variant_attributes)
```php
product_variant_id, attribute_id, attribute_value_id
```

## 5. INVENTARIO

### StockMovement (stock_movements)
```php
id, product_variant_id, type (in/out/adjustment), 
quantity, reference_type, reference_id, notes,
created_by, created_at
```

## 6. CARRITO DE COMPRAS

### Cart (carts)
```php
id, session_id, customer_id (nullable), created_at, updated_at
```

### CartItem (cart_items)
```php
id, cart_id, product_variant_id, quantity, price, 
created_at, updated_at
```

## 7. ÓRDENES Y VENTAS

### Order (orders)
```php
id, order_number, customer_id, status (pending/processing/shipped/delivered/cancelled),
subtotal, tax_amount, shipping_cost, discount_amount, total_amount,
currency, payment_status, payment_method, shipping_method_id,
billing_address_id, shipping_address_id, notes, 
shipped_at, delivered_at, created_at, updated_at
```

### OrderItem (order_items)
```php
id, order_id, product_variant_id, product_name, product_sku,
variant_attributes_json, quantity, unit_price, total_price,
created_at, updated_at
```

### OrderStatusHistory (order_status_histories)
```php
id, order_id, status, notes, created_by, created_at
```

## 8. PAGOS

### Payment (payments)
```php
id, order_id, payment_method (transbank/transfer), 
transaction_id, amount, status (pending/completed/failed/refunded),
gateway_response_json, created_at, updated_at
```

### TransbankTransaction (transbank_transactions)
```php
id, payment_id, token, buy_order, session_id, 
amount, status, vci, created_at, updated_at
```

## 9. ENVÍOS

### ShippingMethod (shipping_methods)
```php
id, name, description, price_type (fixed/weight_based),
base_price, price_per_kg, min_weight, max_weight,
estimated_days, is_active, created_at, updated_at
```

### ShippingZone (shipping_zones)
```php
id, shipping_method_id, region_id, additional_cost, 
created_at, updated_at
```

### Shipment (shipments)
```php
id, order_id, tracking_number, carrier, status,
shipped_at, estimated_delivery, delivered_at, 
created_at, updated_at
```

## RELACIONES PRINCIPALES

1. **User** → hasOne → **Customer**
2. **Customer** → hasMany → **Address**
3. **Product** → belongsTo → **Category**
4. **Product** → hasMany → **ProductVariant**
5. **ProductVariant** → belongsToMany → **AttributeValue**
6. **Order** → hasMany → **OrderItem**
7. **Order** → hasMany → **Payment**
8. **Region** → hasMany → **Commune**

## ÍNDICES RECOMENDADOS

- `products`: slug, category_id, is_active
- `product_variants`: product_id, sku, is_active
- `orders`: customer_id, status, created_at
- `order_items`: order_id, product_variant_id
- `stock_movements`: product_variant_id, created_at
- `addresses`: customer_id, type
- `payments`: order_id, status

## CONFIGURACIÓN ESPECÍFICA CHILE

### Datos iniciales requeridos:
- 16 regiones de Chile en tabla `regions`
- 346 comunas en tabla `communes`
- Métodos de pago: Transbank WebPay Plus, Transferencia Bancaria
- Moneda: CLP (Peso Chileno)
