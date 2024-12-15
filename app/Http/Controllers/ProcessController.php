<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProcessController extends Controller
{

    public function handlePayment(Request $request)
    {

        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You need to be logged in to place an order.',
            ]);
        }

        try {

            $request->merge([
                'cart' => is_string($request->input('cart'))
                    ? json_decode($request->input('cart'), true)
                    : $request->input('cart'),
            ]);


            $validated = $request->validate([
                'full_name' => 'required|string',
                'email' => 'required|email',
                'shipping_address' => 'required|string',
                'phone' => 'required|string',
                'payment_method' => 'required|in:cod,paypal',
                'cart' => 'required|array',
            ]);


            Log::info('Decoded cart', ['cart' => $validated['cart']]);


            $order = Order::create([
                'user_id' => Auth::id(),
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'shipping_address' => $validated['shipping_address'],
                'phone' => $validated['phone'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
            ]);


            foreach ($validated['cart'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }


            Log::info('Order created successfully', ['order_id' => $order->id]);
            session()->forget('cart');

            return response()->json([
                'status' => 'success',
                'message' => 'Order has been placed successfully!',
            ]);
        } catch (\Exception $e) {

            Log::error('Error processing payment', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);


            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
            ]);
        }

    }

}
