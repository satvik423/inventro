<x-navbar>
<div class="container">
    <div class="table-container">
        <table class="productTable">
            <h2>Product List</h2>
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
            </div>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    @if ($role == 'admin')
                    <th>Stock</th>
                    @endif
                    <th>Category</th> 
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>₹{{ number_format($product->price, 2) }}</td>
                    @if ($role == 'admin')
                    <td>{{ $product->stock }}</td>
                    @endif
                    <td>{{ $product->category->name ?? 'No Category' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>
    @if ($role == 'admin')
            <div class="create-container">
        <h2>Add New Product</h2>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" id="price" name="price" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock Quantity</label>
                <input type="number" id="stock" name="stock" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select id="category" name="category_id" class="form-control" required>
                    <option value="" disabled selected>Select Category</option>
                    @foreach($products as $product)
                        <option value="{{ $product->category_id }}">{{ $product->category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-submit">Add Product</button>
        </form>
    </div>
    @endif

</div>
</x-navbar>