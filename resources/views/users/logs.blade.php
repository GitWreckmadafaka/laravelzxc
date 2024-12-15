<!-- resources/views/admin/logs/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs</title>
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
        <div class="card">
            <div class="card-header">
                <h5>Action Logs</h5>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('admin.logs') }}" class="mb-3">
                    <div class="form-row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search Logs" value="{{ old('search', $searchTerm) }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" value="{{ old('date_from', $dateFrom) }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" value="{{ old('date_to', $dateTo) }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Logs Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Details</th>
                            <th scope="col">Date</th>
                            <th scope="col">Admin ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->details }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $log->admin_id }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back to User List</a>
            </div>
        </div>
    </div>
</body>
</html>
