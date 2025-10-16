<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentMethodsWidget extends ChartWidget
{
    protected ?string $heading = 'Ventas por Método de Pago';

    protected static ?int $sort = 5;

    protected ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $paymentMethods = Order::select('payment_method', DB::raw('SUM(total_amount) as total'))
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->groupBy('payment_method')
            ->get();

        $labels = [];
        $data = [];
        $colors = [];

        $colorMap = [
            'webpay' => '#44AD49',
            'transfer' => '#3B82F6',
            'cash' => '#F59E0B',
        ];

        foreach ($paymentMethods as $method) {
            $methodName = match ($method->payment_method) {
                'webpay' => 'WebPay Plus',
                'transfer' => 'Transferencia',
                'cash' => 'Efectivo',
                default => ucfirst($method->payment_method),
            };

            $labels[] = $methodName;
            $data[] = $method->total;
            $colors[] = $colorMap[$method->payment_method] ?? '#6B7280';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (CLP)',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
