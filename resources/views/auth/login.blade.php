<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-header h2 {
            font-size: 1.5rem;
            color: #007bff;
        }
        .btn-google {
            background-color: #db4437;
            color: white;
        }
        .btn-google:hover {
            background-color: #c13525;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="login-container">
            <div class="login-header">
                <h2>Login</h2>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>

            <div class="mt-3">
                <hr>

                <a href="{{ route('google.login') }}" class="btn btn-google btn-block">Sign in with Google</a><br>
                <a href="{{ route('login.github') }}" class="btn btn-dark btn-block">Sign in with Github</a>
            </div>


            <p class="mt-3 text-center">Don't have an account? <a href="/register">Register here</a></p>
            <p class="text-center"><a href="/password/reset">Forgot Your Password?</a></p>
        </div>
    </div>
</body>
</html>
