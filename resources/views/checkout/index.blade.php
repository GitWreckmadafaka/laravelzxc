<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.min.css">
</head>
<body>

<div class="container">
    <h1 class="mt-5">Checkout</h1>

    
    @if(session('cart') && count(session('cart')) > 0)
        <div class="checkout-form">
            <h3>Your Cart</h3>
            <ul class="list-group">
              
                @foreach(session('cart') as $id => $product)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $product['name'] }} (x{{ $product['quantity'] }})</span>
                        <span>₱{{ number_format($product['price'] * $product['quantity'], 2) }}</span>
                    </li>
                @endforeach
            </ul>

            <p class="mt-3"><strong>Total:</strong> ₱{{ number_format($total, 2) }}</p>

          
            <form id="checkout-form" action="{{ route('payment.process') }}" method="POST">
                @csrf

               
                <input type="hidden" name="cart" value="{{ json_encode(session('cart')) }}" />

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"
                           value="{{ old('full_name') ?? (auth()->user() ? auth()->user()->name : '') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email') ?? (auth()->user() ? auth()->user()->email : '') }}" required>
                </div>

                <div class="form-group">
                    <label for="shipping_address">Shipping Address</label>
                    <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="form-control" required>
                        <option value="cod">Cash on Delivery</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <div id="paypal-button-container" class="mt-3"></div>
                <button type="submit" class="btn btn-success mt-3" id="checkout-submit">Proceed to Payment</button>
            </form>
        </div>
    @else
        <p>Your cart is empty. Please add items to your cart first.</p>
    @endif
</div>




<script>
    document.getElementById('checkout-form').addEventListener('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        const paymentMethod = document.querySelector('select[name="payment_method"]').value;

        if (paymentMethod === 'paypal') {
            displayPayPalButton(formData); 
            document.getElementById('checkout-submit').style.display = 'none'; 
        } else {
            submitCOD(formData); 
        }
    });

    function displayPayPalButton(formData) {
       
        document.getElementById('paypal-button-container').innerHTML = '';

   
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '{{ number_format($total, 2) }}',
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    formData.append('paypal_order_id', data.orderID);
                    formData.append('paypal_payer_id', details.payer.payer_id);
                    formData.append('paypal_payment_status', 'completed');

                  
                    fetch("{{ route('payment.process') }}", {
                        method: "POST",
                        body: formData,
                    }).then(response => response.json())
                      .then(data => {
                          if (data.status === 'success') {
                              Swal.fire('Payment Successful!', data.message, 'success')
                                  .then(() => window.location.href = '/home');
                          } else {
                              Swal.fire('Payment Failed', data.message, 'error');
                          }
                      }).catch(error => {
                          console.error('Error:', error);
                          Swal.fire('Error', 'Something went wrong.', 'error');
                      });
                });
            },
            onError: function (err) {
                console.error('Error:', err);
                Swal.fire('Payment Error', 'An error occurred while processing your payment.', 'error');
            }
        }).render('#paypal-button-container');
    }

    function submitCOD(formData) {
        fetch("{{ route('payment.process') }}", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('Order Successful!', data.message, 'success')
                    .then(() => window.location.href = '/home');
            } else {
                Swal.fire('Order Failed', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Something went wrong.', 'error');
        });
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://www.paypal.com/sdk/js?client-id=ARMJqV4wSZXj9zBpIJMb4ukNx9VwHvDSH1WZHv6BEahiVDEKhFZUWOV5FQSnRP3ALI4zHmXW2Up0ThOy&components=buttons"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.all.min.js"></script>


<script src="https://www.paypal.com/sdk/js?client-id=ARMJqV4wSZXj9zBpIJMb4ukNx9VwHvDSH1WZHv6BEahiVDEKhFZUWOV5FQSnRP3ALI4zHmXW2Up0ThOy&currency=PHP"></script>
</body>
</html>
