<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $role = User::where('id', auth()->id())->value('role');
        // dd($cartItems);
        return view('cart.index', compact('cartItems', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (Cart::where('user_id', auth()->id())->where('product_id', $request->product_id)->exists()) {
            return redirect()->back()->with('error', 'Product already in cart!');
        }
        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function destroy($id)
    {

        $cartItem = Cart::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
