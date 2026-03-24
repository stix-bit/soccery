<?php

namespace App\Charts;

use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;

class SalesCharts
{
    public function buildSalesBarChart(Carbon $startDate, Carbon $endDate): Chart
    {
        $dailyRows = DB::table('order_product')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('DATE(orders.created_at) as sale_date')
            ->selectRaw('SUM(order_product.quantity * products.price) as total_sales')
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');

        $labels = [];
        $salesData = [];

        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $dayKey = $cursor->toDateString();
            $labels[] = $cursor->format('M d');
            $salesData[] = round((float) ($dailyRows[$dayKey]->total_sales ?? 0), 2);
            $cursor->addDay();
        }

        $chart = new Chart();
        $chart->labels($labels);
        $chart->dataset('Sales Amount', 'bar', $salesData)
            ->backgroundColor('rgba(37, 99, 235, 0.6)')
            ->color('rgba(37, 99, 235, 1)')
            ->options(['borderWidth' => 1]);

        $chart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ]);

        return $chart;
    }

    public function buildProductPieChart(Carbon $startDate, Carbon $endDate, ?int $limit = null): Chart
    {
        $query = DB::table('order_product')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->join('products', 'products.id', '=', 'order_product.product_id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('products.name as product_name')
            ->selectRaw('SUM(order_product.quantity * products.price) as total_sales')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sales');

        if ($limit !== null) {
            $query->limit($limit);
        }

        $productRows = $query->get();

        $pieLabels = $productRows->pluck('product_name')->toArray();
        $pieData = $productRows->map(fn ($row) => round((float) $row->total_sales, 2))->toArray();

        if (empty($pieLabels)) {
            $pieLabels = ['No sales'];
            $pieData = [1];
        }

        $pieColors = [
            '#2563eb', '#16a34a', '#ea580c', '#9333ea', '#0891b2',
            '#dc2626', '#ca8a04', '#7c3aed', '#0f766e', '#be123c',
        ];

        $chart = new Chart();
        $chart->labels($pieLabels);
        $chart->dataset('Product Sales Share', 'pie', $pieData)
            ->backgroundColor(array_slice($pieColors, 0, count($pieLabels)))
            ->color('#ffffff')
            ->options(['borderWidth' => 2]);

        $chart->options([
            'responsive' => true,
            'maintainAspectRatio' => false,
        ]);

        return $chart;
    }
}
