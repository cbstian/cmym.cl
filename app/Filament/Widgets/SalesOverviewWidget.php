<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SalesOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Ventas del mes actual
        $currentMonthSales = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->sum('total_amount');

        // Ventas del mes anterior
        $previousMonthSales = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->sum('total_amount');

        // Calcular porcentaje de cambio
        $salesChange = $previousMonthSales > 0
            ? (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100
            : 0;

        // Órdenes pendientes
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)
            ->count();

        // Órdenes del mes
        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Ticket promedio
        $averageOrderValue = Order::where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->avg('total_amount') ?? 0;

        return [
            Stat::make('Ventas del Mes', '$'.Number::format($currentMonthSales, locale: 'es', precision: 0))
                ->description(($salesChange >= 0 ? '+' : '').number_format($salesChange, 1).'% vs. mes anterior')
                ->descriptionIcon($salesChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesChange >= 0 ? 'success' : 'danger')
                ->chart($this->getMonthlyChart()),

            Stat::make('Órdenes del Mes', Number::format($monthlyOrders, locale: 'es', precision: 0))
                ->description('Total de órdenes')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),

            Stat::make('Órdenes Pendientes', Number::format($pendingOrders, locale: 'es', precision: 0))
                ->description('Requieren atención')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Ticket Promedio', '$'.Number::format($averageOrderValue, locale: 'es', precision: 0))
                ->description('Valor promedio de orden')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }

    protected function getMonthlyChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)
                ->where('payment_status', Order::PAYMENT_STATUS_PAID)
                ->sum('total_amount');
            $data[] = $sales / 1000; // Simplificar para el gráfico
        }

        return $data;
    }
}
