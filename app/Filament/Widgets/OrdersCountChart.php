<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersCountChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Orders Count by Month';

    protected function getData(): array
    {
        $driver = Order::query()->getConnection()->getDriverName();
        $yearMonthSelect = $driver === 'sqlite'
            ? 'strftime(\'%Y\', date) as year, cast(strftime(\'%m\', date) as integer) as month, COUNT(*) as count'
            : 'YEAR(date) as year, MONTH(date) as month, COUNT(*) as count';

        $orders = Order::query()
            ->selectRaw($yearMonthSelect)
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
                $monthIndex = (int) $order->month - 1;
                $data[$monthIndex] = $order->count;
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
