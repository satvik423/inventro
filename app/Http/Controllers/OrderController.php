<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\order_histories;
use App\Models\orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $fulfillableItems = [];
        $unavailableItems = [];

        // Categorize items based on stock
        foreach ($cartItems as $cartItem) {
            $quantity = $request->input("quantities.{$cartItem->id}", 1);
            $product = $cartItem->product;

            if ($product->stock >= $quantity) {
                $fulfillableItems[] = ['cartItem' => $cartItem, 'quantity' => $quantity];
            } else {
                $unavailableItems[] = $product->name;
            }
        }

        if (empty($fulfillableItems)) {
            return redirect()->back()->with('error', 'No items could be ordered due to insufficient stock.');
        }

        // Create order
        $order = orders::create([
            'user_id' => $user->id,
            'order_code' => 'ORD-' . Str::upper(Str::random(5)),
            'total_price' => 0,
        ]);

        $totalPrice = 0;

        foreach ($fulfillableItems as $itemData) {
            $cartItem = $itemData['cartItem'];
            $quantity = $itemData['quantity'];
            $product = $cartItem->product;

            $price = $product->price * $quantity;
            $totalPrice += $price;

            order_histories::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'status' => 'pending',
            ]);

            $product->decrement('stock', $quantity);
            $cartItem->delete();
        }

        $order->update(['total_price' => $totalPrice]);

        // feedback message
        $message = 'Order placed successfully!';
        if (!empty($unavailableItems)) {
            $message .= ' However, the following items were not ordered due to insufficient stock: ' . implode(', ', $unavailableItems);
        }

        return redirect()->route('cart.show')->with('success', $message);
    }
}
