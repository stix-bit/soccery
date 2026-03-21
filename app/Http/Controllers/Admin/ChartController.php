<?php

namespace App\Http\Controllers\Admin;

use App\Charts\SalesCharts;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChartController extends Controller
{
    public function index(Request $request): View
    {
        $timezone = config('app.timezone', 'UTC');

        $startInput = $request->input('start_date');
        $endInput = $request->input('end_date');

        $endDate = $endInput
            ? Carbon::parse($endInput, $timezone)
            : now($timezone)->endOfDay();

        $startDate = $startInput
            ? Carbon::parse($startInput, $timezone)
            : $endDate->copy()->subDays(29)->startOfDay();

        if ($startDate->greaterThan($endDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        $startDate = $startDate->copy()->startOfDay();
        $endDate = $endDate->copy()->endOfDay();

        $pieScope = $request->input('pie_scope', 'all');
        if (! in_array($pieScope, ['all', 'top10'], true)) {
            $pieScope = 'all';
        }
        $pieLimit = $pieScope === 'top10' ? 10 : null;

        $charts = new SalesCharts();
        $salesBarChart = $charts->buildSalesBarChart($startDate, $endDate);
        $productPieChart = $charts->buildProductPieChart($startDate, $endDate, $pieLimit);

        return view('admin.charts.index', [
            'salesBarChart' => $salesBarChart,
            'productPieChart' => $productPieChart,
            'pieScope' => $pieScope,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
