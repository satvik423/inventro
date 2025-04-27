<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\order_histories;
use App\Models\orders;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\OrderStatusUpdated;
use App\Models\Notifications;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = orders::orderby('created_at', 'desc')->get();
        // dd($orders);
        $role = User::where('id', auth()->id())->value('role');
        return view('order.index', compact('orders', 'role'));
    }

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
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            Notifications::create([
                'user_id' => $admin->id,
                'message' => "A new order #{$order->order_code} has been placed by user #{$user->id} with order value of {$totalPrice}.",
            ]);
        }
        if (!empty($unavailableItems)) {
            $message .= ' However, the following items were not ordered due to insufficient stock: ' . implode(', ', $unavailableItems);
        }

        return redirect()->route('cart.show')->with('success', $message);
    }

    public function show($id)
    {
        $order_id = orders::findOrFail($id);
        $orderItems = order_histories::where('order_id', $id)->with('product')->get();

        $orders = orders::get(); // to populate left side table again
        $role = User::where('id', auth()->id())->value('role');
        // dd($order);
        return view('order.index', compact('order_id', 'orderItems', 'orders', 'role'));
    }


    public function destroy($id)
    {
        $order = orders::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Order removed successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $item = order_histories::with('product')->findOrFail($id);
        $currentStatus = $request->input('current_status');

        switch ($currentStatus) {
            case 'pending':
                $nextStatus = 'shipped';
                break;
            case 'shipped':
                $nextStatus = 'delivered';
                break;
            default:
                return back()->with('success', 'Order delivered already.');
        }

        $item->status = $nextStatus;
        $item->save();

        // Get user email via order_id
        $order = orders::find($item->order_id);
        $user = User::find($order->user_id);

        // Send the email
        if ($user && $user->email) {
            Notifications::create([
                'user_id' => $order->user_id,
                'message' => "Your order #{$order->id} status changed to {$nextStatus}",
            ]);
            Mail::to($user->email)->send(new OrderStatusUpdated($item->product->name, $nextStatus));
        }

        return back()->with('success', 'Order status updated and email sent.');
    }
}
