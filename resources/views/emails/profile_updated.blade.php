<!-- resources/views/emails/profile_updated.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile has been Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 600px;
        }
        h1 {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Profile has been Updated</h1>
        <p>Hello {{ $user->name }},</p>
        <p>Your profile information has been successfully updated. If you did not make this change, please contact support.</p>
        <p>Thank you for using our application!</p>
    </div>
</body>
</html>
