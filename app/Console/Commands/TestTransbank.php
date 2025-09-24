<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Services\TransbankService;
use Illuminate\Console\Command;

class TestTransbank extends Command
{
    protected $signature = 'test:transbank {--test : Actually run the test transaction}';

    protected $description = 'Test Transbank WebPay Plus integration';

    public function handle()
    {
        $this->info('Testing Transbank Integration...');
        $this->newLine();

        $this->info('Configuration:');
        $this->line('Environment: ' . config('services.transbank.environment'));
        $this->line('Commerce Code: ' . config('services.transbank.commerce_code'));
        $this->line('API Key: ' . substr(config('services.transbank.api_key'), 0, 10) . '...');
        $this->newLine();

        if ($this->option('test')) {
            $this->testTransbankConnection();
        } else {
            $this->info('Use --test flag to run actual transaction test');
        }

        return 0;
    }

    protected function testTransbankConnection()
    {
        try {
            $transbankService = app(TransbankService::class);

            $customer = Customer::first();
            if (!$customer) {
                $this->error('No customer found in database. Run AddressSeeder first.');
                return;
            }

            $this->info('Creating test order...');

            $order = new Order();
            $order->customer_id = $customer->id;
            $order->status = Order::STATUS_PENDING;
            $order->subtotal = 1000;
            $order->shipping_cost = 0;
            $order->discount_amount = 0;
            $order->total_amount = 1000;
            $order->currency = 'CLP';
            $order->payment_status = Order::PAYMENT_STATUS_PENDING;
            $order->shipping_address_id = $customer->addresses()->first()?->id;
            $order->billing_address_id = $customer->addresses()->first()?->id;
            $order->save();

            $this->info('Created test order: ' . $order->id . ' - ' . $order->order_number);
            $this->line('Total amount: $' . number_format($order->total_amount, 0, ',', '.') . ' CLP');

            $returnUrl = url('/payment/webpay/return');

            $this->info('Attempting to create transaction...');

            $result = $transbankService->createTransaction($order, $returnUrl);

            if ($result['success']) {
                $this->info('✅ Transaction created successfully!');
                $this->line('Token: ' . $result['token']);
                $this->line('URL: ' . $result['url']);
                $this->line('Payment ID: ' . $result['payment']->id);
                $this->line('Amount sent to Transbank: $' . number_format($result['payment']->amount, 0, ',', '.') . ' CLP');
                $this->newLine();
                $this->info('You can visit the URL to complete the test payment.');
            } else {
                $this->error('❌ Transaction creation failed:');
                $this->line('Error: ' . $result['error']);
                if (isset($result['transbank_error'])) {
                    $this->line('Transbank Error: ' . $result['transbank_error']);
                }
            }

            $order->delete();

        } catch (\Exception $e) {
            $this->error('Exception occurred: ' . $e->getMessage());
            $this->line('File: ' . $e->getFile());
            $this->line('Line: ' . $e->getLine());
        }
    }
}
