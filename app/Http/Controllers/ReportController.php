<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OrderItem;
class ReportController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now();

        $weeklySales = OrderItem::where('created_at', '>=', $currentDate->subDays(7))
            ->selectRaw('SUM(quantity * price) as total_sales, COUNT(*) as items_sold')
            ->first();

        $currentDate = Carbon::now();
        $monthlySales = OrderItem::where('created_at', '>=', $currentDate->subDays(30))
            ->selectRaw('SUM(quantity * price) as total_sales, COUNT(*) as items_sold')
            ->first();

        $currentDate = Carbon::now();
        $yearlySales = OrderItem::whereYear('created_at', $currentDate->year)
            ->selectRaw('SUM(quantity * price) as total_sales, COUNT(*) as items_sold')
            ->first();

        return view('sales.index', compact('weeklySales', 'monthlySales', 'yearlySales'));
    }

    public function breakdown(Request $request)
    {
        $period = $request->input('period');
        $currentDate = Carbon::now();

        if ($period === 'weekly') {
            $sales = OrderItem::where('created_at', '>=', $currentDate->subDays(7))->get();
        } elseif ($period === 'monthly') {
            $sales = OrderItem::where('created_at', '>=', $currentDate->subDays(30))->get();
        } elseif ($period === 'yearly') {
            $sales = OrderItem::whereYear('created_at', $currentDate->year)->get();
        } else {
            return response()->json(['error' => 'Invalid period'], 400);
        }

        return response()->json($sales);
    }
}
