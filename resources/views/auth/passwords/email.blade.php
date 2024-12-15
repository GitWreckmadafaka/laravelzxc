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
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #007bff; /* Border color */
        }
        .reset-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .reset-header h2 {
            font-size: 1.5rem;
            color: #007bff; /* Header color */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="reset-container">
            <div class="reset-header">
                <h2>Reset Your Password</h2>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Send Password Reset Link</button>
            </form>

            <p class="mt-3 text-center"><a href="{{ route('login') }}">Back to Login</a></p>
        </div>
    </div>
</body>
</html>
