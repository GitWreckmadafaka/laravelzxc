<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            vertical-align: middle;
        }
        .btn {
            margin-right: 5px;
        }
        h1, h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/admin/users" class="btn btn-secondary mb-3 float-end">Back</a>
        <h1>Sales Report</h1><br>


        <canvas id="salesChart" width="400" height="200"></canvas><br>

        <h2>Sales Summary</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Total Sales</th>
                    <th>Orders</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Weekly</td>
                    <td>${{ $weeklySales->total_sales ?? 0 }}</td>
                    <td>{{ $weeklySales->items_sold ?? 0 }}</td>
                    <td><button class="btn btn-info" onclick="fetchBreakdown('weekly')">View Breakdown</button></td>
                </tr>
                <tr>
                    <td>Monthly</td>
                    <td>${{ $monthlySales->total_sales ?? 0 }}</td>
                    <td>{{ $monthlySales->items_sold ?? 0 }}</td>
                    <td><button class="btn btn-info" onclick="fetchBreakdown('monthly')">View Breakdown</button></td>
                </tr>
                <tr>
                    <td>Yearly</td>
                    <td>${{ $yearlySales->total_sales ?? 0 }}</td>
                    <td>{{ $yearlySales->items_sold ?? 0 }}</td>
                    <td><button class="btn btn-info" onclick="fetchBreakdown('yearly')">View Breakdown</button></td>
                </tr>
            </tbody>
        </table>

        <h2>Breakdown</h2>
        <table id="breakdownTable" class="table table-bordered hidden">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Ordered At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div><br>

    <script>
        const weeklySales = @json($weeklySales->total_sales ?? 0);
    const monthlySales = @json($monthlySales->total_sales ?? 0);
    const yearlySales = @json($yearlySales->total_sales ?? 0);

    const salesData = {
        labels: ['Weekly', 'Monthly', 'Yearly'],
        datasets: [{
            label: 'Total Sales',
            data: [weeklySales, monthlySales, yearlySales],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

    const config = {
        type: 'bar',
        data: salesData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    enabled: true,
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true,
                    max: 2000
                }
            }
        }
    };

    // Render the chart
    const salesChart = new Chart(
        document.getElementById('salesChart'),
        config
    );

        // Function to fetch breakdown data based on the period
        async function fetchBreakdown(period) {
            const response = await fetch('/orders/breakdown', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ period })
            });
            const data = await response.json();

            if (response.ok) {
                const breakdownTable = document.getElementById('breakdownTable');
                const tbody = breakdownTable.querySelector('tbody');
                tbody.innerHTML = '';

                data.forEach(item => {
                    const row = `<tr>
                        <td>${item.id}</td>
                        <td>${item.order_id}</td>
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price}</td>
                        <td>${item.created_at}</td>
                        <td>${item.updated_at}</td>
                    </tr>`;
                    tbody.innerHTML += row;
                });

                breakdownTable.classList.remove('hidden');
            } else {
                alert(data.error || 'Failed to fetch breakdown data.');
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
