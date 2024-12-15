<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - {{ $product->name }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Product Details</h1>

        <div class="card">
            <div class="card-header">
                <h2>{{ $product->name }}</h2>
            </div>
            <div class="card-body">
         
                <div class="mb-3">
                    @if($product->image_url)
                        <img src="{{ asset('storage/' . $product->image_url) }}" class="card-img-top" alt="{{ $product->name }}" style="max-width: 300px;">
                    @else
                        <img src="{{ asset('storage/default-placeholder.png') }}" class="card-img-top" alt="No Image Available" style="max-width: 300px;">
                    @endif
                </div>

            
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>{{ $product->description }}</p>
                </div>

       
                <div class="mb-3">
                    <strong>Price:</strong>
                    <p>${{ number_format($product->price, 2) }}</p>
                </div>

           
                <div class="mb-3">
                    <strong>Category:</strong>
                    <p>{{ $product->category }}</p>
                </div>

              
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Back to Product List</a>
            </div>
        </div>
    </div>
</body>
</html>
