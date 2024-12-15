<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class OrderHController extends Controller
{
    public function index()
    {
        $users = User::all();
        $products = Product::all();
        $categories = Category::all();
        $orders = Order::all();

        return view('users.index', compact('users', 'products', 'categories', 'orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered',
        ]);

        $order->payment_status = $request->input('status');
        $order->save();

        DB::table('logs')->insert([
            'admin_id' => auth()->id(),
            'action' => 'Update Order Status',
            'details' => "Updated status of order ID: {$order->id} to {$order->payment_status}",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }

    public function cancel($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
        }

        if ($order->payment_status == 'pending') {
            $order->orderItems()->delete();
            $order->delete();

            DB::table('logs')->insert([
                'admin_id' => auth()->id(),
                'action' => 'Cancel Order',
                'details' => "Canceled order ID: {$order->id}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.orders.index')->with('success', 'Order has been canceled successfully.');
        } elseif ($order->payment_status == 'shipped' || $order->payment_status == 'delivered') {
            return response()->json([
                'status' => 'error',
                'message' => 'Order cannot be canceled because it has already been shipped or delivered.'
            ]);
        }

        return redirect()->route('admin.orders.index')->with('error', 'Order cannot be canceled in its current state.');
    }
}
