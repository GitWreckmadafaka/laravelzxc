<!-- resources/views/auth/reset.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .reset-container {
            max-width: 500px;
            margin: auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #007bff; /* Enhanced border */
            /* Optionally, add padding to create space inside the border */
        }
        .reset-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .reset-header h2 {
            font-size: 1.5rem;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="reset-container">
            <div class="reset-header">
                <h2>Reset Your Password</h2>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ implode('', $errors->all(':message')) }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $email) }}" required>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>

            <p class="mt-3 text-center"><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
