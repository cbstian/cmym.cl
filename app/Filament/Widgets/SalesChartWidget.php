<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class SalesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Ventas por Día';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = '30';

    protected function getData(): array
    {
        $days = (int) $this->filter;
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');

            $dailySales = Order::whereDate('created_at', $date)
                ->where('payment_status', Order::PAYMENT_STATUS_PAID)
                ->sum('total_amount');

            $data[] = $dailySales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (CLP)',
                    'data' => $data,
                    'borderColor' => 'rgb(68, 173, 73)',
                    'backgroundColor' => 'rgba(68, 173, 73, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Últimos 7 días',
            '30' => 'Últimos 30 días',
            '90' => 'Últimos 90 días',
        ];
    }
}
