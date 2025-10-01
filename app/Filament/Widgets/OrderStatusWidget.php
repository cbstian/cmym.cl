<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrderStatusWidget extends ChartWidget
{
    protected ?string $heading = 'Órdenes por Estado';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $statuses = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $labels = [];
        $data = [];
        $colors = [];

        $colorMap = [
            'pending' => '#F59E0B',
            'processing' => '#3B82F6',
            'shipped' => '#8B5CF6',
            'delivered' => '#44AD49',
            'cancelled' => '#EF4444',
        ];

        foreach ($statuses as $status) {
            $statusName = match ($status->status) {
                'pending' => 'Pendiente',
                'processing' => 'Procesando',
                'shipped' => 'Enviado',
                'delivered' => 'Entregado',
                'cancelled' => 'Cancelado',
                default => ucfirst($status->status),
            };

            $labels[] = $statusName;
            $data[] = $status->count;
            $colors[] = $colorMap[$status->status] ?? '#6B7280';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de Órdenes',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
