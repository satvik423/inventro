<title>
    @if ($role == 'admin')
        Inventro - Admin
    @else
        Inventro - Cart
    @endif
</title>
<link rel="stylesheet" href="{{ asset('css/cartPage.css') }}">

<x-navbar>
    <h2>Your Cart</h2>

    @if (session('success'))
        <div id="flash-message" class="success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div id="flash-message" class="error">
            {{ session('error') }}
        </div>
    @endif

    @if ($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <form id="orderForm" action="{{ route('order.place') }}" method="POST">
            @csrf
            @foreach ($cartItems as $cartItem)
                <div class="cart-item">
                    <h3>{{ $cartItem->product->name }}</h3>
                    <p>{{ $cartItem->product->description }}</p>
                    <p>Price: ₹{{ number_format($cartItem->product->price, 2) }}</p>
                    <p>Category: {{ $cartItem->product->category->name ?? 'No Category' }}</p>

                    <div class="quantity-selector">
                        <button type="button" onclick="updateQuantity({{ $cartItem->id }}, -1)">-</button>
                        <input type="number" name="quantities[{{ $cartItem->id }}]" id="quantity-{{ $cartItem->id }}" value="1" min="1">
                        <button type="button" onclick="updateQuantity({{ $cartItem->id }}, 1)">+</button>
                    </div>
                </div>
            @endforeach
            <button type="submit">Place Order</button>
        </form>

        <!-- Separate Remove Buttons -->
        @foreach ($cartItems as $cartItem)
            <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Remove {{ $cartItem->product->name }}</button>
            </form>
        @endforeach
    @endif
</x-navbar>

<script>
    function updateQuantity(id, change) {
        let quantityInput = document.getElementById('quantity-' + id);
        let newValue = parseInt(quantityInput.value) + change;
        if (newValue >= 1) {
            quantityInput.value = newValue;
        }
    }
</script>
