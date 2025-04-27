

<title>
    @if ($role == 'admin')
        Inventro - Admin
    @else
        Inventro - Orders
    @endif
</title>
    <link rel="stylesheet" href="{{ asset('css/orderPage.css') }}">
<x-navbar>
<div class="container">
    <div class="table-container">
        <h2>All Orders</h2>
         @if (session('success'))
                <div id="flash-message" class="success">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div id="flash-message" class="error">
                    {{ session('error') }}
                </div>
            @endif
        <table class="productTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Code</th>
                    <th>Total Price</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>₹{{ number_format($order->total_price, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}</td>
                        <td>
                            <a href="{{ route('order.show', $order->id) }}" class="search-button">View</a>
                            <form action="{{ route('order.remove', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="search-button" style="background-color: #dc3545;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@if(isset($order_id) && isset($orderItems))
        <div class="detail-container">
            <div class="detail-card">
                <h2>🧾 Order Details</h2>
                <p><strong>Order Code:</strong> {{ $order_id->order_code }}</p>
                <p><strong>Total Price:</strong> ₹{{ number_format($order_id->total_price, 2) }}</p>
                <p><strong>Created At:</strong> {{ \Carbon\Carbon::parse($order_id->created_at)->format('d M Y, h:i A') }}</p>

                <h3>🛒 Products in this Order</h3>
                <ul class="item-list">
                   @foreach($orderItems as $item)
                        <li class="item-card">
                            <div class="item-name">{{ $item->product->name }}</div>
                            Category: <span>{{ $item->product->category->name }}</span>
                            <div class="item-meta">
                                Quantity: <span>{{ $item->quantity }}</span> |
                                <form action="{{ route('order.updateStatus', $item->id) }}" method="POST" class="status-form">
                                    @csrf
                                    <input type="hidden" name="current_status" value="{{ $item->status }}">
                                    <button type="submit" class="status-button {{ $item->status }}">
                                        {{ ucfirst($item->status) }}
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


</div>
</x-navbar>