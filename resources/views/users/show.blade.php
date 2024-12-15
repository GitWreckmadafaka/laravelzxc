<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5>{{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Age:</strong> {{ $user->age }}</p>
                <p><strong>Birthdate:</strong> {{ $user->birthdate }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($user->gender) }}</p>
                <p><strong>Status:</strong> {{ $user->is_active ? 'Active' : 'Inactive' }}</p>
                <p><strong>Last Login:</strong> {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('Y-m-d H:i:s') : 'Never' }}</p>

                <form action="{{ route('admin.users.toggleAdmin', $user->id) }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                    </button>
                </form>

                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back to User List</a>
            </div>
        </div>
    </div>
</body>
</html>
