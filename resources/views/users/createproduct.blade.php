<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Add New Product</h1>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Select a Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="brand">Brand</label>
                <select name="brand" id="brand" class="form-control" required>
                    <option value="">Select a Brand</option>
                </select>
                @error('brand') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="color">Color</label>
                <select name="color" id="color" class="form-control" required>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}" {{ old('color') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                    @endforeach
                </select>
                @error('color') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock') }}" required>
                @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                @error('price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" name="image" id="image" class="form-control-file" required>
                @error('image') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>

        <a href="/admin/products" class="btn btn-secondary mt-3">Back to Product List</a>
    </div>

    <script>
        $(document).ready(function() {
            // When the category changes
            $('#category').on('change', function() {
                var categoryId = $(this).val(); // Get the selected category ID
                if (categoryId) {
                    // Send an AJAX request to get the brands for this category
                    $.ajax({
                        url: '/admin/brands/' + categoryId, // Your route to fetch brands
                        type: 'GET',
                        success: function(data) {
                            // Clear the brand dropdown
                            $('#brand').empty();
                            $('#brand').append('<option value="">Select a Brand</option>'); // Default option

                            // Add brands to the dropdown
                            $.each(data.brands, function(key, brand) {
                                $('#brand').append('<option value="' + brand.id + '">' + brand.name + '</option>');
                            });
                        }
                    });
                } else {
                    // If no category is selected, clear the brand dropdown
                    $('#brand').empty();
                    $('#brand').append('<option value="">Select a Brand</option>');
                }
            });
        });
    </script>
</body>
</html>
