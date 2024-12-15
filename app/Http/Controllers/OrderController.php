<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function checkout()
    {

        $cart = Session::get('cart');
        $total = array_sum(array_column($cart, 'price'));

        return view('checkout', compact('cart', 'total'));
    }

    public function placeOrder(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'payment_method' => 'required|string',
            'terms' => 'required|accepted',
        ]);


        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $request->total,
            'status' => 'pending',
        ]);


        $cart = Session::get('cart');
        foreach ($cart as $product_id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product_id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }


        Session::forget('cart');


        if ($request->payment_method === 'cod') {
            return redirect()->route('home')->with('success', 'Order placed successfully. Thank you!');
        }



        return redirect()->route('home')->with('success', 'Order placed successfully. Thank you!');
    }
    public function index()
    {

        $orders = Order::where('user_id', Auth::id())
                       ->with('orderItems')
                       ->get();


        return view('orders.index', compact('orders'));
    }
}
