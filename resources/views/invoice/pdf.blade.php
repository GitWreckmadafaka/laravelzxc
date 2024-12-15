<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .invoice-header h1 {
            font-size: 2rem;
            color: #2C3E50;
        }
        .invoice-header p {
            font-size: 1.1rem;
            margin: 5px 0;
        }
        .invoice-details p {
            font-size: 1rem;
            margin: 5px 0;
        }
        .invoice-items {
            margin-top: 30px;
        }
        .invoice-items h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #2980B9;
        }
        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-items table, .invoice-items th, .invoice-items td {
            border: 1px solid #ddd;
        }
        .invoice-items th, .invoice-items td {
            padding: 12px;
            text-align: left;
        }
        .invoice-items th {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            font-size: 1.4rem;
            margin-top: 20px;
            text-align: right;
        }
        .total p {
            margin: 10px 0;
            color: #27AE60;
        }
        .currency {
            font-weight: normal;
            color: #7F8C8D;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 1rem;
            color: #7F8C8D;
        }
    </style>
</head>
<body>

    <div class="invoice-header">
        <h1>Invoice #{{ $order->id }}</h1>
        <p><strong>Customer:</strong> {{ $order->user->name }}</p> <!-- Assuming the order has a user relation -->
        <p><strong>Seller:</strong> THAT TIME I GOT REINCARNATED AS A SELLER NAMED FAZ</p>
        <p><strong>Status:</strong> {{ $order->payment_status }}</p>
        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
    </div>

    <div class="invoice-details">
        <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
    </div>

    <div class="invoice-items">
        <h3>Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $orderTotal = 0;
                @endphp
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>₱{{ number_format($item->price, 2) }}</td>
                        <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @php
                        $orderTotal += $item->price * $item->quantity;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total">
        <p>Total Price: <span class="currency">₱</span>{{ number_format($orderTotal, 2) }}</p>
    </div>

    <div class="footer">
        <p>Thank you for shopping with THAT TIME I GOT REINCARNATED AS A SELLER NAMED FAZp!</p>
    </div>

</body>
</html>
