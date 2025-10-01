<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class EcommerceSettings extends Settings
{
    public array $emails_notifications_orders;

    public string $bank_details;

    public string $email_confirmation_payment;

    public static function group(): string
    {
        return 'ecommerce';
    }
}
