<!-- resources/views/auth/otp.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .otp-container {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #007bff;
        }
        .otp-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .otp-header h2 {
            font-size: 1.5rem;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="otp-container">
            <div class="otp-header">
                <h2>Enter OTP</h2>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('otp.verify') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="otp">One-Time Password</label>
                    <input type="text" class="form-control" id="otp" name="otp" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
            </form>

            <p class="mt-3 text-center"><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
