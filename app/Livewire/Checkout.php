<?php

namespace App\Livewire;

use App\Mail\TransferPaymentMail;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Location\Region;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\CheckoutService;
use App\Settings\EcommerceSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Checkout extends Component
{
    // Datos del usuario - persistidos en sesión
    #[Session]
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Session]
    #[Validate('required|email|max:255')]
    public $email = '';

    #[Session]
    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Session]
    #[Validate('nullable|string|max:12')]
    public $rut = '';

    #[Session]
    #[Validate('nullable|string|max:255')]
    public $company_name = '';

    // Datos de dirección de envío - persistidos en sesión
    #[Session]
    #[Validate('required|exists:regions,id')]
    public $shipping_region_id = '';

    #[Session]
    #[Validate('required|exists:communes,id')]
    public $shipping_commune_id = '';

    #[Session]
    #[Validate('required|string|max:255')]
    public $shipping_address_line_1 = '';

    #[Session]
    #[Validate('nullable|string|max:255')]
    public $shipping_address_line_2 = '';

    // Datos de facturación - persistidos en sesión
    #[Session]
    public $same_as_shipping = true;

    #[Session]
    #[Validate('required_if:same_as_shipping,false|nullable|exists:regions,id')]
    public $billing_region_id = '';

    #[Session]
    #[Validate('required_if:same_as_shipping,false|nullable|exists:communes,id')]
    public $billing_commune_id = '';

    #[Session]
    #[Validate('required_if:same_as_shipping,false|nullable|string|max:255')]
    public $billing_address_line_1 = '';

    #[Session]
    #[Validate('nullable|string|max:255')]
    public $billing_address_line_2 = '';

    // Notas adicionales - persistidas en sesión
    #[Session]
    #[Validate('nullable|string|max:500')]
    public $order_notes = '';

    // Método de pago - persistido en sesión
    #[Session]
    #[Validate('required|in:webpay,transfer')]
    public $payment_method = 'webpay';

    // Empresa courier para envíos fuera de RM - persistido en sesión
    #[Session]
    #[Validate('required_if:isRegionRM,false|nullable|string')]
    public $courier_company = '';

    // Datos para el componente
    public $cartItems = [];

    public $regions = [];

    public $communes = [];

    public $billing_communes = [];

    public $courierCompanies = [];

    public $isRegionRM = false;

    public $shippingType = 'fixed'; // 'fixed' para RM, 'to_pay' para otras regiones

    public $subtotal = 0;

    public $shipping_cost = 0;

    public $total = 0;

    public $isLoading = false;

    public function mount(): void
    {
        $this->loadCart();
        $this->loadRegions();
        $this->loadCommunesFromSession();
        $this->loadCourierCompanies();
        $this->checkIfRegionIsRM();
        $this->calculateTotals();

        // Verificar si el pago anterior falló
        if (session()->has('checkout_payment_failed')) {
            session()->flash('error', 'El pago anterior no pudo ser procesado. Por favor, verifica los datos e inténtalo nuevamente.');
            session()->forget('checkout_payment_failed');
        }
    }

    public function updated($property): void
    {
        // Asegurar que las comunas estén cargadas después de cualquier actualización
        // Esto es especialmente importante después de errores de validación
        $this->ensureCommunesAreLoaded();
    }

    public function updatedShippingRegionId($regionId): void
    {
        $this->shipping_commune_id = '';
        $this->communes = [];

        if ($regionId) {
            $region = Region::find($regionId);
            if ($region) {
                $this->communes = $region->communesActive();
                $this->isRegionRM = $region->abbreviation === 'RM';
                $this->shippingType = $this->isRegionRM ? 'fixed' : 'to_pay';
            }
        }

        $this->calculateShippingCost();

        $this->calculateTotals();
    }

    public function updatedShippingCommuneId(): void
    {
        $this->calculateTotals();
    }

    public function updatedBillingRegionId($regionId): void
    {
        $this->billing_commune_id = '';
        $this->billing_communes = [];

        if ($regionId) {
            $region = Region::find($regionId);
            if ($region) {
                $this->billing_communes = $region->communesActive();
            }
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
                    'courier_company' => ! $this->isRegionRM ? $this->courier_company : null,
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
                            'product_description' => $product->short_description,
                            'product_image_path' => $product->image_primary_path,
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['product_price'],
                            'total_price' => $item['product_price'] * $item['quantity'],
                            'product_attributes' => ! empty($item['attributes']) ? $item['attributes'] : null,
                        ]);

                        // Actualizar stock del producto
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                }

                // Procesar según el método de pago
                if ($this->payment_method === 'webpay') {
                    // Redirigir al proceso de pago con Webpay
                    $this->redirect(route('payment.webpay.init', $order), navigate: false);
                } else {
                    // Para transferencia bancaria, crear registro de pago pendiente
                    Payment::create([
                        'order_id' => $order->id,
                        'method' => Payment::METHOD_TRANSFER,
                        'amount' => $order->total_amount,
                        'currency' => $order->currency,
                        'status' => Payment::STATUS_PENDING,
                    ]);

                    // Marcar checkout como completado y limpiar carrito
                    CheckoutService::markCheckoutComplete();

                    // Enviar correo con instrucciones de transferencia
                    try {
                        $order = $order->load(['customer.user', 'items', 'shippingAddress', 'billingAddress']);
                        $settings = app(EcommerceSettings::class);

                        // Enviar instrucciones al cliente
                        Mail::to($order->customer->user->email)
                            ->send(new TransferPaymentMail(
                                $order,
                                $settings->bank_details,
                                $settings->email_confirmation_payment,
                                $settings->emails_notifications_orders
                            ));

                        // Notificar a los administradores sobre la nueva orden
                        if (! empty($settings->emails_notifications_orders)) {
                            foreach ($settings->emails_notifications_orders as $adminEmail) {
                                Mail::to($adminEmail)
                                    ->send(new TransferPaymentMail(
                                        $order,
                                        $settings->bank_details,
                                        $settings->email_confirmation_payment,
                                        $settings->emails_notifications_orders
                                    ));
                            }
                        }
                    } catch (\Exception $e) {
                        // Log error but don't fail the order creation
                        Log::error('Error enviando correo de instrucciones de transferencia: '.$e->getMessage(), [
                            'order_id' => $order->id,
                        ]);
                    }

                    $this->redirect(route('payment.transfer.instructions', ['order' => $order->id]), navigate: false);
                }
            });

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Los errores de validación se manejan automáticamente por Livewire
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error en processCheckout: '.$e->getMessage(), [
                'exception' => $e,
                'payment_method' => $this->payment_method,
                'cart_items' => count($this->cartItems),
                'email' => $this->email,
            ]);
            session()->flash('error', 'Ocurrió un error al procesar tu pedido. Por favor, inténtalo nuevamente.');
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Método público para limpiar manualmente el carrito y datos del checkout
     * Útil para casos especiales o llamadas desde otros componentes
     */
    public function clearCheckoutData(): void
    {
        CheckoutService::markCheckoutComplete();
        $this->loadCart(); // Recargar carrito vacío
    }

    /**
     * Método público para verificar si hay datos de checkout en sesión
     */
    public function hasPersistedData(): bool
    {
        return CheckoutService::hasCheckoutData();
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

    private function loadCommunesFromSession(): void
    {
        // Cargar comunas de envío si existe región guardada en sesión
        if ($this->shipping_region_id) {
            $region = Region::find($this->shipping_region_id);
            if ($region) {
                $this->communes = $region->communesActive();
            }
        }

        // Cargar comunas de facturación si existe región guardada en sesión y no es la misma dirección
        if (! $this->same_as_shipping && $this->billing_region_id) {
            $region = Region::find($this->billing_region_id);
            if ($region) {
                $this->billing_communes = $region->communesActive();
            }
        }
    }

    private function ensureCommunesAreLoaded(): void
    {
        if ($this->shipping_region_id && count($this->communes) === 0) {
            $region = Region::find($this->shipping_region_id);
            if ($region) {
                $this->communes = $region->communesActive();
            }
        }

        // Cargar comunas de facturación si hay región seleccionada, no es la misma dirección y no hay comunas cargadas
        if (! $this->same_as_shipping && $this->billing_region_id && empty($this->billing_communes)) {
            $region = Region::find($this->billing_region_id);
            if ($region) {
                $this->billing_communes = $region->communesActive();
            }
        }
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

        $region = Region::find($this->shipping_region_id);

        if (! $region) {
            return 0;
        }

        // Si es Región Metropolitana, usar costos por comuna
        if ($region->abbreviation === 'RM') {
            if (! $this->shipping_commune_id) {
                return 0;
            }

            $settings = app(EcommerceSettings::class);
            $tempShippingCostsRM = $settings->shipping_costs_rm ?? [];
            $shippingCostsRM = [];

            if (count($tempShippingCostsRM) > 0 AND is_array($tempShippingCostsRM)) {
                foreach($tempShippingCostsRM as $entry) {
                    if (isset($entry['commune_id']) && isset($entry['cost'])) {
                        $shippingCostsRM[$entry['commune_id']] = $entry['cost'];
                    }
                }
            }

            return $shippingCostsRM[$this->shipping_commune_id] ?? 10000; // Default 10000 si no está configurado
        }

        // Para otras regiones, el costo es "por pagar" (se muestra $0 en el checkout)
        return 0;
    }

    private function loadCourierCompanies(): void
    {
        $settings = app(EcommerceSettings::class);
        $this->courierCompanies = $settings->courier_companies ?? [];
    }

    private function checkIfRegionIsRM(): void
    {
        if ($this->shipping_region_id) {
            $region = Region::find($this->shipping_region_id);
            if ($region) {
                $this->isRegionRM = $region->abbreviation === 'RM';
                $this->shippingType = $this->isRegionRM ? 'fixed' : 'to_pay';
            }
        }
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'CMYM-'.date('YmdHis');
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
