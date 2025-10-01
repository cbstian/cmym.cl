<?php

namespace App\Console\Commands;

use App\Mail\ContactFormMail;
use App\Mail\OrderConfirmationMail;
use App\Models\FormContact;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailgunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailgun:test
                            {type=simple : Type of test email (simple, contact, order, config)}
                            {--email=sebastian@procodigo.cl : Email address to send test to}
                            {--show-config : Show Mailgun configuration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Mailgun email sending functionality and configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->argument('type');
        $email = $this->option('email');

        // Show configuration if requested
        if ($this->option('show-config') || $type === 'config') {
            $this->showMailgunConfig();
            if ($type === 'config') {
                return 0;
            }
        }

        $this->info("🚀 Testing Mailgun with type: {$type} to: {$email}");
        $this->info('📤 Mail driver: '.config('mail.default'));
        $this->info('🌐 Mailgun domain: '.config('services.mailgun.domain'));

        try {
            switch ($type) {
                case 'simple':
                    $this->testSimpleMail($email);
                    break;
                case 'contact':
                    $this->testContactMail($email);
                    break;
                case 'order':
                    $this->testOrderMail($email);
                    break;
                default:
                    $this->error("Unknown type: {$type}. Available types: simple, contact, order, config");

                    return 1;
            }

            $this->info('✅ Email sent successfully!');
            $this->line('🔍 Check your inbox and spam folder.');
            $this->line('📊 You can also check Mailgun dashboard for delivery status.');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error sending email: '.$e->getMessage());
            $this->line('💡 Check your Mailgun configuration and credentials.');
            $this->showMailgunConfig();

            return 1;
        }
    }

    private function testSimpleMail(string $email): void
    {
        Mail::raw('🎉 ¡Hola desde CMYM.cl!

Este es un email de prueba para verificar que Mailgun está funcionando correctamente.

✅ Servidor de envío: Mailgun
📧 Destinatario: '.$email.'
⏰ Enviado: '.now()->format('d/m/Y H:i:s').'
🌐 Dominio: '.config('app.url').'

Si recibiste este email, ¡todo está funcionando perfectamente!

Saludos,
Equipo CMYM.cl', function ($message) use ($email) {
            $message->to($email)
                ->subject('🚀 Prueba de Mailgun - CMYM.cl')
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        $this->line('📧 Simple test email sent to: '.$email);
    }

    private function testContactMail(string $email): void
    {
        // Create a fake contact for testing
        $contact = new FormContact([
            'nombre' => 'Juan Pérez',
            'correo' => 'juan@example.com',
            'telefono' => '+56912345678',
            'direccion' => 'Santiago, Chile',
            'mensaje' => 'Este es un mensaje de prueba desde el sistema de contacto.',
        ]);
        $contact->created_at = now();

        Mail::to($email)->send(new ContactFormMail($contact));
        $this->line('📧 Contact form test email sent.');
    }

    private function testOrderMail(string $email): void
    {
        // Find or create a test order
        $order = Order::with(['customer.user', 'items', 'shippingAddress', 'billingAddress'])->first();

        if (! $order) {
            $this->error('No orders found in database. Create an order first.');

            return;
        }

        Mail::to($email)->send(new OrderConfirmationMail($order));
        $this->line("📦 Order confirmation test email sent for order #{$order->order_number}.");
    }

    private function showMailgunConfig(): void
    {
        $this->line('');
        $this->info('📋 Mailgun Configuration:');
        $this->line('  Mail Driver: '.config('mail.default'));
        $this->line('  Mailgun Domain: '.config('services.mailgun.domain'));
        $this->line('  Mailgun Endpoint: '.config('services.mailgun.endpoint'));
        $this->line('  From Address: '.config('mail.from.address'));
        $this->line('  From Name: '.config('mail.from.name'));
        $this->line('  Secret Key: '.(config('services.mailgun.secret') ? '✅ Configured' : '❌ Not set'));
        $this->line('');
    }
}
