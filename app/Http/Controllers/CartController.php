<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        // Find the product
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('home')->with('error', 'Product not found.');
        }

        // Check if the product is in stock
        if ($product->stock <= 0) {
            return redirect()->route('home')->with('error', 'Sorry, this product is out of stock.');
        }

        // Get the cart from the session
        $cart = session()->get('cart', []);

        // Check if the product is already in the cart
        if (isset($cart[$id])) {
            // If the product is already in the cart, increase the quantity
            // Ensure that the added quantity doesn't exceed the stock
            if ($cart[$id]['quantity'] < $product->stock) {
                $cart[$id]['quantity']++;
            } else {
                return redirect()->route('home')->with('error', 'Sorry, you cannot add more of this product to the cart than the available stock.');
            }
        } else {
            // If the product is not in the cart, add it with a quantity of 1
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $product->image_url,
            ];
        }

        // Save the cart back to the session
        session()->put('cart', $cart);

        // Decrease the product stock by 1
        $product->decrement('stock');

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function updateQuantity(Request $request)
    {
        // Get the cart from the session
        $cart = session()->get('cart', []);

        // Loop through the quantities array and update the cart
        foreach ($request->quantity as $id => $quantity) {
            if (isset($cart[$id])) {
                // Ensure that the updated quantity doesn't exceed the stock
                $product = Product::find($id);
                if ($quantity <= $product->stock) {
                    $cart[$id]['quantity'] = $quantity;
                } else {
                    return redirect()->route('cart.index')->with('error', 'Not enough stock for ' . $product->name);
                }
            }
        }

        // Save the updated cart to the session
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function removeFromCart(Request $request, $id)
    {
        // Get the cart from the session
        $cart = session()->get('cart', []);

        // Check if the item exists in the cart
        if (isset($cart[$id])) {
            // Get the product from the cart and increment its stock
            $product = Product::find($id);
            if ($product) {
                $product->increment('stock', $cart[$id]['quantity']);
            }

            // Remove the product from the cart
            unset($cart[$id]);

            // Save the updated cart to the session
            session()->put('cart', $cart);

            // Return success response
            return response()->json(['success' => true, 'message' => 'Product removed from cart.']);
        }

        // Return failure response if item not found
        return response()->json(['success' => false, 'message' => 'Product not found in cart.']);
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }
}
