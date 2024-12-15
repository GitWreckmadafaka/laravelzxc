<!-- resources/views/emails/custom_reset.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h1 {
            color: #333;
        }
        .email-body {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="email-body">
            <p>Hello,</p>
            <p>We received a request to reset your password. If you did not make this request, you can ignore this message.</p>
            <p>To reset your password, click the button below:</p>
            <a href="{{ url(route('password.reset', ['token' => $token], false)) }}" class="button">Reset Password</a>
        </div>
        <div class="email-footer">
            <p>If you are unable to click the button, copy and paste the following URL into your browser:</p>
            <p><a href="{{ url(route('password.reset', ['token' => $token], false)) }}">{{ url(route('password.reset', ['token' => $token], false)) }}</a></p>
        </div>
    </div>
</body>
</html>
