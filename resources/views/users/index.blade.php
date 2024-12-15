<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User and Product Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn {
            margin-right: 5px;
        }
        .card-img-top {
            max-width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        .card {
            margin-bottom: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        .d-flex {
            justify-content: center;
        }
    </style>
    <script>
        function showProductModal(product) {
            document.getElementById("productModalTitle").innerText = product.name;
            document.getElementById("productModalDescription").innerText = product.description;
            document.getElementById("productModalPrice").innerText = "₱" + product.price.toFixed(2);
            document.getElementById("productModalCategory").innerText = product.category;
            document.getElementById("productModalColor").innerText = product.color;
            document.getElementById("productModalBrand").innerText = product.brand;
            document.getElementById("productModalCreatedAt").innerText = product.created_at;
            document.getElementById("productModalUpdatedAt").innerText = product.updated_at;
            document.getElementById("productModalId").innerText = product.id;

            var productImageElement = document.getElementById("productModalImage");
            if (product.image_url) {
                productImageElement.src = "/storage/" + product.image_url;
            } else {
                productImageElement.src = "/storage/default-placeholder.png";
            }

            document.getElementById("deleteProductForm").action = "/admin/products/" + product.id;
            $('#productModal').modal('show');

        }
    </script>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger mb-3">Logout</button>
        </form>
        <a href="{{ route('home') }}" class="btn btn-secondary mb-3 float-right">Go to Dashboard</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info">View</a>
                            <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirmToggle();">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalTitle">Product Name</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img id="productModalImage" class="card-img-top mb-3" src="" alt="Product Image">
                        <p><strong>Description: </strong><span id="productModalDescription">Product Description</span></p>
                        <p><strong>Price: </strong><span id="productModalPrice">$0.00</span></p>
                        <p><strong>Category: </strong><span id="productModalCategory">Category</span></p>
                        <p><strong>Brand: </strong><span id="productModalBrand">Brand</span></p>
                        <p><strong>Color: </strong><span id="productModalColor">Color</span></p>
                        <p><strong>Created At: </strong><span id="productModalCreatedAt">Date</span></p>
                        <p><strong>Updated At: </strong><span id="productModalUpdatedAt">Date</span></p>
                        <p><strong>Product ID: </strong><span id="productModalId">ID</span></p>

                        <form id="deleteProductForm" action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Product</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Product Management</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add New Product</a>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Stock</th> <!-- New Stock column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            <img src="{{ asset($product->image_url ? 'storage/' . $product->image_url : 'storage/default-placeholder.png') }}" class="card-img-top" alt="{{ $product->name }}">
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->category->name ?? 'No category' }}</td>
                        <td>{{ $product->brand->name ?? 'No brand' }}</td>
                        <td>
                            <!-- Show Stock or Sold Out -->
                            @if($product->stock > 0)
                                {{ $product->stock }} Available
                            @else
                                <span class="text-danger">Sold Out</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="showProductModal({
                                id: {{ $product->id }},
                                name: '{{ $product->name }}',
                                description: '{{ $product->description }}',
                                price: {{ $product->price }},
                                category: '{{ $product->category->name ?? 'No category' }}',
                                color: '{{ $product->color->name ?? 'No color' }}',
                                brand: '{{ $product->brand->name ?? 'No brand' }}',
                                image_url: '{{ $product->image_url }}',
                                created_at: '{{ $product->created_at }}',
                                updated_at: '{{ $product->updated_at }}'
                            })">View</button>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="container">

        <h2 class="mt-5">Manage Categories, Colors, and Brands</h2>
        <div class="d-flex mb-3">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success mb-3 mr-2">Add Category</a>
            <a href="{{ route('admin.colors.create') }}" class="btn btn-success mb-3 mr-2">Add Color</a>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-success mb-3">Add Brand</a>

        </div>

        <h2 class="mt-5">View Logs and Sales / Manage Carousel</h2>
        <div class="d-flex mb-3">
            <a href="{{ route('admin.logs') }}" class="btn btn-info mb-3">View Logs</a>
            <a href="{{ route('sales.index') }}" class="btn btn-info mb-3">View Sales</a>
            <a href="{{ route('carousels.index') }}" class="btn btn-info mb-3">Go to Carousel</a>
        </div>

    </div>

    <div class="container">
        <h2>Order Management</h2>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Payment Method</th> <!-- New column for Payment Method -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user ? $order->user->name : 'No user' }}</td>
                        <td>
                            @foreach($order->orderItems as $orderItem)
                                <p>{{ $orderItem->product_name ?? 'No product' }} ({{ $orderItem->quantity }})</p>
                            @endforeach
                        </td>
                        <td>{{ ucfirst($order->payment_status) }}</td>
                        <td>{{ ucfirst($order->payment_method) }}</td> <!-- Display Payment Method -->
                        <td>
                            @if($order->payment_status == 'pending')
                                <!-- Status update form (only for pending orders) -->
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="shipped" {{ $order->payment_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->payment_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </form><br>

                                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</button>
                                </form>
                            @elseif($order->payment_status == 'shipped')
                                <!-- Status update form (only for shipped orders) -->
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="shipped" {{ $order->payment_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->payment_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </form>
                            @elseif($order->payment_status == 'delivered')
                                <!-- Status update form (for delivered orders, disable dropdown) -->
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control" disabled>
                                        <option value="delivered" {{ $order->payment_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
