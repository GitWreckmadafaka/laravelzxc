<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .register-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .register-header h2 {
            font-size: 1.5rem;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="register-container">
            <div class="register-header">
                <h2>Register</h2>
            </div>

            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Last Name -->
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>

                <!-- First Name -->
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>

                <!-- Middle Name -->
                <div class="form-group">
                    <label for="middlename">Middle Name</label>
                    <input type="text" class="form-control" id="middlename" name="middlename">
                </div>

                <!-- Age -->
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" class="form-control" id="age" name="age" required min="1">
                </div>

                <!-- Birthdate -->
                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="" disabled selected>Select your gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" required>
                </div>

                <!-- Zip Code -->
                <div class="form-group">
                    <label for="zipcode">Zip Code</label>
                    <input type="text" class="form-control" id="zipcode" name="zipcode" required>
                </div>

                <!-- Profile Image -->
                <div class="form-group">
                    <label for="profile_image">Profile Image</label>
                    <input type="file" class="form-control" id="profile_image" name="profile_image">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <!-- reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6LdpTJYqAAAAADrvTewNBU1qOCpOI00BOwsabXxi"></div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>

            <p class="mt-3 text-center">Already have an account? <a href="{{ route('login.form') }}">Login here</a></p>
        </div>
    </div>
</body>
</html>
