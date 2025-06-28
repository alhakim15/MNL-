<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Dashboard - Debug</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .stat {
            background: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat h3 {
            margin: 0;
            font-size: 24px;
        }

        .stat p {
            margin: 5px 0 0 0;
        }

        .debug {
            background: #ffe6e6;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .payment-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .btn {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><i class="fas fa-credit-card"></i> Payment Dashboard - Debug Mode</h1>

        <div class="debug">
            <h3>Debug Information:</h3>
            <p><strong>User:</strong> {{ auth()->user()->name ?? 'Not logged in' }}</p>
            <p><strong>Total Deliveries:</strong> {{ $totalDeliveries ?? 'undefined' }}</p>
            <p><strong>Pending Payments:</strong> {{ $pendingPayments ?? 'undefined' }}</p>
            <p><strong>Paid Payments:</strong> {{ $paidPayments ?? 'undefined' }}</p>
            <p><strong>Total Amount:</strong> {{ $totalAmount ?? 'undefined' }}</p>
            <p><strong>Recent Payments Count:</strong> {{ isset($recentPayments) ? $recentPayments->count() :
                'undefined' }}</p>
            <p><strong>Pending Payments Count:</strong> {{ isset($pendingPaymentsList) ? $pendingPaymentsList->count() :
                'undefined' }}</p>
        </div>

        <div class="stats">
            <div class="stat">
                <h3>{{ $totalDeliveries ?? 0 }}</h3>
                <p>Total Deliveries</p>
            </div>
            <div class="stat">
                <h3>{{ $pendingPayments ?? 0 }}</h3>
                <p>Pending</p>
            </div>
            <div class="stat">
                <h3>{{ $paidPayments ?? 0 }}</h3>
                <p>Paid</p>
            </div>
            <div class="stat">
                <h3>Rp {{ number_format($totalAmount ?? 0, 0, ',', '.') }}</h3>
                <p>Total Amount</p>
            </div>
        </div>

        @if(isset($pendingPaymentsList) && $pendingPaymentsList->count() > 0)
        <div class="card">
            <h2>Pending Payments</h2>
            @foreach($pendingPaymentsList as $payment)
            <div class="payment-item">
                <h4>{{ $payment->resi }}</h4>
                <p>{{ $payment->fromCity->name ?? 'N/A' }} → {{ $payment->toCity->name ?? 'N/A' }}</p>
                <p>Rp {{ number_format($payment->shipping_cost ?? 0, 0, ',', '.') }}</p>
                <p>{{ $payment->created_at->format('d M Y, H:i') }}</p>
                <a href="{{ route('payment.show', $payment->resi) }}" class="btn">Bayar</a>
            </div>
            @endforeach
        </div>
        @endif

        @if(isset($recentPayments) && $recentPayments->count() > 0)
        <div class="card">
            <h2>Recent Payments</h2>
            @foreach($recentPayments as $payment)
            <div class="payment-item">
                <h4>{{ $payment->resi }} - {{ ucfirst($payment->payment_status) }}</h4>
                <p>{{ $payment->fromCity->name ?? 'N/A' }} → {{ $payment->toCity->name ?? 'N/A' }}</p>
                <p>Rp {{ number_format($payment->shipping_cost ?? 0, 0, ',', '.') }}</p>
                <p>{{ $payment->created_at->format('d M Y, H:i') }}</p>
                @if($payment->payment_status === 'pending')
                <a href="{{ route('payment.show', $payment->resi) }}" class="btn">Bayar</a>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="card">
            <h2>No Payments Found</h2>
            <p>No recent payments to display.</p>
        </div>
        @endif

        <div class="card">
            <h3>Quick Actions</h3>
            <a href="{{ route('deliveries.create') }}" class="btn">Create Delivery</a>
            <a href="{{ route('deliveries.history') }}" class="btn">View History</a>
            <a href="{{ route('home') }}" class="btn">Back to Home</a>
        </div>
    </div>
</body>

</html>