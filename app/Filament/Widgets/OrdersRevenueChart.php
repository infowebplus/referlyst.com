<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersRevenueChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Orders Revenue by Month';

    protected function getData(): array
    {
        $orders = Order::query()
            ->selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(price) as total')
            ->whereNotNull('date')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $years = $orders->pluck('year')->unique()->sort()->values();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $datasets = [];
        $colors = [
            '#f59e0b', // amber-500
            '#ef4444', // red-500
            '#3b82f6', // blue-500
            '#10b981', // green-500
            '#8b5cf6', // violet-500
            '#ec4899', // pink-500
        ];

        foreach ($years as $index => $year) {
            $data = array_fill(0, 12, 0);

            $yearOrders = $orders->where('year', $year);
            foreach ($yearOrders as $order) {
                $data[$order->month - 1] = (float) $order->total;
            }

            $datasets[] = [
                'label' => (string) $year,
                'data' => $data,
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => $colors[$index % count($colors)],
                'fill' => false,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
