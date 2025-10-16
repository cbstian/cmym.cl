<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EcommerceSettings extends Settings
{
    public array $emails_notifications_orders;

    public string $bank_details;

    public string $email_confirmation_payment;

    /**
     * Costos de envío por comuna para la Región Metropolitana
     * Estructura: ['commune_id' => cost_in_clp]
     */
    public array $shipping_costs_rm;

    /**
     * Empresas courier disponibles para envíos fuera de la RM
     * Array de strings con los nombres de las empresas
     */
    public array $courier_companies;

    public static function group(): string
    {
        return 'ecommerce';
    }
}
