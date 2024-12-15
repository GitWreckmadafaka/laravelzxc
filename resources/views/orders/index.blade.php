<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .order {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .order h3 {
            font-size: 1.5rem;
        }
        .order p {
            font-size: 1.1rem;
        }
        .order-items ul {
            list-style-type: none;
            padding-left: 0;
        }
        .order-items li {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .order-total {
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1>Your Orders</h1>

    @if($orders->isEmpty())
        <div class="alert alert-warning">
            You have no orders yet.
        </div>
    @else
        <div id="orders">
            @foreach($orders as $order)
                <div class="order">
                    <h3>Order #{{ $order->id }}</h3>
                    <p><strong>Status:</strong> {{ $order->payment_status }}</p>
                    <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>

                    <div class="order-items">
                        <h4>Items:</h4>
                        <ul>
                            @php
                                $orderTotal = 0;
                            @endphp
                            @foreach($order->orderItems as $item)
                                <li>
                                    <strong>{{ $item->product_name }}</strong> (x{{ $item->quantity }})
                                    - ₱{{ number_format($item->price, 2) }}
                                </li>
                                @php
                                    $orderTotal += $item->price * $item->quantity;
                                @endphp
                            @endforeach
                        </ul>
                    </div>

                    <div class="order-total">
                        <h4>Total Price: ₱{{ number_format($orderTotal, 2) }}</h4>
                        <a href="{{ route('generate.invoice', $order->id) }}"
                            class="btn mt-3 generate-invoice-btn"
                            data-status="{{ $order->payment_status }}">
                            Generate Invoice
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <a href="{{ url('/home') }}" class="btn btn-secondary mt-3">Back to Home</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all the 'Generate Invoice' buttons
        const buttons = document.querySelectorAll('.generate-invoice-btn');

        buttons.forEach(button => {
            const status = button.getAttribute('data-status');

            if (status !== 'delivered') {
                // Disable the button and make it unclickable if the status is not 'delivered'
                button.classList.add('btn-secondary');
                button.classList.remove('btn-primary');
                button.setAttribute('disabled', 'true');
                button.style.pointerEvents = 'none'; // Ensure it's unclickable
            } else {
                // Enable the button if the status is 'delivered'
                button.classList.add('btn-primary');
                button.classList.remove('btn-secondary');
                button.removeAttribute('disabled');
                button.style.pointerEvents = 'auto'; // Make it clickable
            }
        });
    });
</script>

</body>
</html>
