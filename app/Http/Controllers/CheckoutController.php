<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function showCheckoutForm()
    {

        $cart = session('cart', []);


        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty!');
        }


        $total = $this->calculateCartTotal($cart);


        return view('checkout.index', compact('cart', 'total'));

    }

    public function processCheckout(Request $request)
    {

        return redirect()->route('checkout.success')->with('success', 'Your order has been placed!');
    }

    private function calculateCartTotal($cart)
    {

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }


}
