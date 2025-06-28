<!DOCTYPE html>
<html>

<head>
    <title>Payment Debug</title>
</head>

<body>
    <h1>Payment Debug Information</h1>
    <p><strong>Current URL:</strong> {{ request()->fullUrl() }}</p>
    <p><strong>Order ID:</strong> {{ request()->get('order_id') }}</p>
    <p><strong>Status Code:</strong> {{ request()->get('status_code') }}</p>
    <p><strong>Transaction Status:</strong> {{ request()->get('transaction_status') }}</p>
    <p><strong>User Authenticated:</strong> {{ auth()->check() ? 'Yes' : 'No' }}</p>
    @if(auth()->check())
    <p><strong>User Name:</strong> {{ auth()->user()->name }}</p>
    @endif

    <hr>

    <h2>All Request Data:</h2>
    <pre>{{ print_r(request()->all(), true) }}</pre>

    <hr>

    <p><a href="{{ route('home') }}">Go to Home</a></p>
</body>

</html>