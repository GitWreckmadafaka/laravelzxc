<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css">
</head>
<body>

    <div class="container mt-5">
        <h1>Your Cart</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($cart && count($cart) > 0)
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $item)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" width="50" />
                                {{ $item['name'] }}
                            </td>
                            <td>₱{{ number_format($item['price'], 2) }}</td>
                            <td>
                                <input type="number" name="quantity[{{ $id }}]" value="{{ $item['quantity'] }}" min="1" class="form-control" style="width: 60px;" />
                            </td>
                            <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="removeItem({{ $id }})">Remove</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary">Update Cart</button>
                <a href="{{ route('checkout') }}" class="btn btn-success">Proceed to Checkout</a>
                <a href="{{ url('/home') }}" class="btn btn-secondary">Back to Home</a>
            </form>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.all.min.js"></script>

    <script>
        function removeItem(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to remove this item from your cart?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove!',
                cancelButtonText: 'No, Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('cart.remove', '') }}/" + id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            _method: 'POST'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Removed!',
                                text: data.message,
                                icon: 'success'
                            }).then(() => {
                                location.reload(); // Reload the page to update the cart
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => console.log('Error:', error));
                }
            });
        }
    </script>
</body>
</html>
