<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container my-4">
        <h1>Your Wishlist</h1>


        @if($wishlist->isEmpty())
            <div class="alert alert-warning" role="alert">
                Your wishlist is empty.
            </div>
        @else
            <div class="row">
                @foreach($wishlist as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $product->image_url) }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <p class="card-text"><strong>Price:</strong> ₱{{ number_format($product->price, 2) }}</p>

                            </div>
                            <div class="card-footer d-flex justify-content-between">

                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                                </form>


                                <form action="{{ route('wishlist.remove', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <a href="{{ url('/home') }}" class="btn btn-secondary mt-3">Back to Home</a>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
