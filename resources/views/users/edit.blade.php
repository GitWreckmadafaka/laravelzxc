<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required>{{ $product->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Price (₱)</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="brand_id">Brand</label>
                <select class="form-control" id="brand_id" name="brand_id" required>
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @if($brand->id == $product->brand_id) selected @endif>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="color_id">Color</label>
                <select class="form-control" id="color_id" name="color_id" required>
                    <option value="">Select Color</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}" @if($color->id == $product->color_id) selected @endif>{{ $color->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Add stock input field -->
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" class="form-control" id="image" name="image">
                @if($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" class="mt-2" width="100">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="/admin/users" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
