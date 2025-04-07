
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
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="import_file">Import Products (Excel)</label>
                <input type="file" id="import_file" name="import_file" class="form-control" accept=".xlsx, .csv" required>
            </div>
            <button type="submit" class="btn-submit">Import Products</button>
        </form>

    </div>