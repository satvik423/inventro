<title>
    @if ($role == 'admin')
        Inventro - Admin
    @else
        Inventro - Products
    @endif
</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/productPage.css') }}">
<x-navbar>
<div class="container">
    <div class="table-container">
        <table class="productTable">
            <h2>Product List</h2>
            @if (session('success'))
                <div id="flash-message" class="success">
                    {{ session('success') }}
                </div>
            @elseif (session('error'))
                <div id="flash-message" class="error">
                    {{ session('error') }}
                </div>
            @endif

            <div class="search-container">
                <!-- Search Form -->
                <form action="{{ route('products.show') }}" method="GET">
                    <select id="category" name="category" class="form-control">
                        <option value=""  selected>All Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" class="search-input" placeholder="Search..." name="search" value="{{ request('search') }}">

                    <button type="submit" class="search-button">🔍</button>

                    <button type="submit" name="sort" value="asc" class="btn-submit">Sort Asc ↑</button>
                    <button type="submit" name="sort" value="desc" class="btn-submit">Sort Desc ↓</button>
                </form>


                <!-- Export Form (Separate) -->
                @if ($role == 'admin')
                <form action="{{ route('products.export') }}" method="GET">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <button type="submit" class="btn-submit">Excel</button>
                    <button type="submit" name="export" value="pdf" class="btn-submit">PDF</button>
                </form>
                @endif
                <!-- Bell Icon for Notifications -->
                <div id="notification-bell" class="notification-wrapper">
                    <i class="fa fa-bell"></i>
                    <span class="notification-count" id="notification-count" style="display: none;"></span>

                    <div class="notification-dropdown" id="notification-dropdown" style="display: none;">
                        <h4>Notifications</h4>
                        <ul id="notification-list"></ul>
                    </div>
                </div>
            </div>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th> 
                    @if ($role == 'admin')
                    <th>Stock</th>
                    @endif
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>₹{{ number_format($product->price, 2) }}</td>

                    <td>{{ $product->category->name ?? 'No Category' }}</td>
                    @if ($role == 'user')
                    <td>
                        @if(in_array($product->id, $inCartProductIds))
                            <button class="btn-disabled" >In Cart</button>
                        @else
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn-submit">Add to Cart</button>
                            </form>
                        @endif
                    </td>
                    @endif
                    @if ($role == 'admin')
                        <td>{{ $product->stock }}</td>
                        <td>
                            <form action="{{ route('products.remove', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-submit">Delete</button>
                            </form>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>
    @if ($role == 'admin')
          @include('products.create')
    @endif
</div>
</x-navbar>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const bell = document.getElementById("notification-bell");
    const dropdown = document.getElementById("notification-dropdown");
    const countSpan = document.getElementById("notification-count");
    const list = document.getElementById("notification-list");

    function fetchNotifications() {
        fetch("{{ route('notifications.fetch') }}")
            .then(response => response.json())
            .then(data => {
                list.innerHTML = '';
                if (data.notifications.length > 0) {
                    countSpan.innerText = data.unread_count;
                    countSpan.style.display = 'inline-block';

                    data.notifications.forEach(notification => {
                        const li = document.createElement('li');
                        li.textContent = notification.message;
                        list.appendChild(li);
                    });
                } else {
                    countSpan.style.display = 'none';
                    list.innerHTML = '<li>No new notifications.</li>';
                }
            });
    }

    bell.addEventListener("click", () => {
        // Toggle dropdown visibility
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";

        // Call backend to mark as read
        fetch("{{ route('notifications.markAllAsRead') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            countSpan.style.display = 'none';
        });
    });

    fetchNotifications();
    setInterval(fetchNotifications, 30000); // Poll every 30 seconds
});
</script>
