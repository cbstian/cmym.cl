<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\Commune;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Checkout extends Component
{
    // Datos del usuario
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Validate('nullable|string|max:12')]
    public $rut = '';

    #[Validate('nullable|string|max:255')]
    public $company_name = '';

    // Datos de dirección de envío
    #[Validate('required|exists:regions,id')]
    public $shipping_region_id = '';

    #[Validate('required|exists:communes,id')]
    public $shipping_commune_id = '';

    #[Validate('required|string|max:255')]
    public $shipping_address_line_1 = '';

    #[Validate('nullable|string|max:255')]
    public $shipping_address_line_2 = '';

    // Datos de facturación
    public $same_as_shipping = true;

    #[Validate('required_if:same_as_shipping,false|nullable|exists:regions,id')]
    public $billing_region_id = '';

    #[Validate('required_if:same_as_shipping,false|nullable|exists:communes,id')]
    public $billing_commune_id = '';

    #[Validate('required_if:same_as_shipping,false|nullable|string|max:255')]
    public $billing_address_line_1 = '';

    #[Validate('nullable|string|max:255')]
    public $billing_address_line_2 = '';

    // Notas adicionales
    #[Validate('nullable|string|max:500')]
    public $order_notes = '';

    // Método de pago
    #[Validate('required|in:webpay,transfer')]
    public $payment_method = 'webpay';

    // Datos para el componente
    public $cartItems = [];

    public $regions = [];

    public $communes = [];

    public $billing_communes = [];

    public $subtotal = 0;

    public $shipping_cost = 0;

    public $total = 0;

    public $isLoading = false;

    public function mount(): void
    {
        $this->loadCart();
        $this->loadRegions();
        $this->calculateTotals();
    }

    public function updatedShippingRegionId($regionId): void
    {
        $this->shipping_commune_id = '';
        $this->communes = [];

        if ($regionId) {
            $this->communes = Commune::where('region_id', $regionId)
                ->orderBy('name')
                ->get();
        }

        $this->calculateTotals();
    }

    public function updatedBillingRegionId($regionId): void
    {
        $this->billing_commune_id = '';
        $this->billing_communes = [];

        if ($regionId) {
            $this->billing_communes = Commune::where('region_id', $regionId)
                ->orderBy('name')
                ->get();
        }
    }

    public function updatedSameAsShipping(): void
    {
        if ($this->same_as_shipping) {
            $this->billing_region_id = '';
            $this->billing_commune_id = '';
            $this->billing_address_line_1 = '';
            $this->billing_address_line_2 = '';
            $this->billing_communes = [];
        }
    }

    public function processCheckout(): void
    {
        $this->isLoading = true;

        try {
            $this->validate();

            if (empty($this->cartItems)) {
                session()->flash('error', 'Tu carrito está vacío');

                return;
            }

            DB::transaction(function () {
                // Crear o obtener usuario
                $user = User::firstOrCreate(
                    ['email' => $this->email],
                    [
                        'name' => $this->name,
                        'phone' => $this->phone,
                        'password' => bcrypt('temporary_'.uniqid()),
                    ]
                );

                // Crear o actualizar customer
                $customer = Customer::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'rut' => $this->rut,
                        'company_name' => $this->company_name,
                    ]
                );

                // Crear dirección de envío
                $shippingAddress = Address::create([
                    'customer_id' => $customer->id,
                    'type' => 'shipping',
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'region_id' => $this->shipping_region_id,
                    'commune_id' => $this->shipping_commune_id,
                    'address_line_1' => $this->shipping_address_line_1,
                    'address_line_2' => $this->shipping_address_line_2,
                    'is_default' => false,
                ]);

                // Crear dirección de facturación
                if ($this->same_as_shipping) {
                    $billingAddress = Address::create([
                        'customer_id' => $customer->id,
                        'type' => 'billing',
                        'name' => $this->name,
                        'phone' => $this->phone,
                        'region_id' => $this->shipping_region_id,
                        'commune_id' => $this->shipping_commune_id,
                        'address_line_1' => $this->shipping_address_line_1,
                        'address_line_2' => $this->shipping_address_line_2,
                        'is_default' => false,
                    ]);
                } else {
                    $billingAddress = Address::create([
                        'customer_id' => $customer->id,
                        'type' => 'billing',
                        'name' => $this->name,
                        'phone' => $this->phone,
                        'region_id' => $this->billing_region_id,
                        'commune_id' => $this->billing_commune_id,
                        'address_line_1' => $this->billing_address_line_1,
                        'address_line_2' => $this->billing_address_line_2,
                        'is_default' => false,
                    ]);
                }

                // Crear orden
                $order = Order::create([
                    'order_number' => $this->generateOrderNumber(),
                    'customer_id' => $customer->id,
                    'status' => 'pending',
                    'subtotal' => $this->subtotal,
                    'shipping_cost' => $this->shipping_cost,
                    'discount_amount' => 0,
                    'total_amount' => $this->total,
                    'currency' => 'CLP',
                    'payment_status' => 'pending',
                    'payment_method' => $this->payment_method,
                    'billing_address_id' => $billingAddress->id,
                    'shipping_address_id' => $shippingAddress->id,
                    'notes' => $this->order_notes,
                ]);

                // Crear items de la orden
                foreach ($this->cartItems as $item) {
                    $product = Product::find($item['product_id']);

                    if ($product) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'product_sku' => $product->sku,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['product_price'],
                            'total_price' => $item['product_price'] * $item['quantity'],
                        ]);

                        // Actualizar stock del producto
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                }

                // Limpiar el carrito
                $this->clearCart();

                // Procesar según el método de pago
                if ($this->payment_method === 'webpay') {
                    // Redirigir al proceso de pago con Webpay
                    $this->redirect(route('payment.webpay.init', $order), navigate: false);
                } else {
                    // Para transferencia bancaria, mostrar instrucciones
                    session()->flash('success', 'Tu pedido ha sido creado exitosamente. Número de orden: '.$order->order_number.' - Recibirás las instrucciones de pago por email.');
                    $this->redirect(route('home'), navigate: true);
                }
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Los errores de validación se manejan automáticamente por Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al procesar tu pedido. Por favor, inténtalo nuevamente.');
        } finally {
            $this->isLoading = false;
        }
    }

    private function loadCart(): void
    {
        try {
            $sessionUserId = session('cart_user_id');

            if (! $sessionUserId) {
                $this->cartItems = [];

                return;
            }

            $sessionKey = 'cart_'.crc32($sessionUserId);
            $rawCartItems = session($sessionKey, []);

            $this->cartItems = collect($rawCartItems)->map(function ($item, $index) {
                return [
                    'index' => $index,
                    'product_id' => $item['itemable_id'] ?? null,
                    'product_name' => $item['product_name'] ?? 'Producto',
                    'product_sku' => $item['product_sku'] ?? null,
                    'product_price' => $item['product_price'] ?? 0,
                    'product_image' => $item['product_image'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'attributes' => $item['attributes'] ?? [],
                ];
            })->toArray();
        } catch (\Exception $e) {
            $this->cartItems = [];
        }
    }

    private function loadRegions(): void
    {
        $this->regions = Region::orderBy('name')->get();
    }

    private function calculateTotals(): void
    {
        $this->subtotal = collect($this->cartItems)->sum(function ($item) {
            return ($item['product_price'] ?? 0) * ($item['quantity'] ?? 0);
        });

        // Calcular costo de envío basado en región (ejemplo simple)
        $this->shipping_cost = $this->calculateShippingCost();

        // Total sin impuestos (IVA incluido en precios)
        $this->total = $this->subtotal + $this->shipping_cost;
    }

    private function calculateShippingCost(): float
    {
        if (! $this->shipping_region_id) {
            return 0;
        }

        // Lógica simple de envío - en el futuro puede ser más compleja
        $region = Region::find($this->shipping_region_id);

        if (! $region) {
            return 0;
        }

        // Ejemplo: Región Metropolitana = $5000, otras regiones = $8000
        return $region->code === 'RM' ? 5000 : 8000;
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    private function clearCart(): void
    {
        $sessionUserId = session('cart_user_id');

        if ($sessionUserId) {
            $sessionKey = 'cart_'.crc32($sessionUserId);
            session([$sessionKey => []]);
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
