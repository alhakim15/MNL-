<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Dashboard - Liners Shipping</title>
    <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-dashboard {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .dashboard-header h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .dashboard-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .stat-icon.paid {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
        }

        .stat-icon.amount {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #333;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .content-card-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-card-header h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .view-all-btn {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .payment-item {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: background 0.3s ease;
        }

        .payment-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .payment-item:last-child {
            border-bottom: none;
        }

        .payment-header {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .payment-resi {
            font-weight: 600;
            color: white;
            font-size: 1.1rem;
        }

        .payment-date {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .payment-route {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-amount {
            font-weight: 700;
            font-size: 1.1rem;
            color: #4ade80;
        }

        .payment-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-paid {
            background: rgba(74, 222, 128, 0.2);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.3);
        }

        .status-pending {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .status-failed {
            background: rgba(248, 113, 113, 0.2);
            color: #f87171;
            border: 1px solid rgba(248, 113, 113, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.7);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-pay {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn-pay:hover {
            transform: translateY(-1px);
            color: white;
        }

        .btn-track {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-track:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .payment-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="payment-dashboard">
        <nav class="back-nav">
            <a href="{{ route('home') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Home
            </a>
            <a href="{{ route('deliveries.history') }}" class="back-button">
                <i class="fas fa-shipping-fast"></i> Deliveries
            </a>
        </nav>

        <div class="dashboard-container">
            <div class="dashboard-header">
                <h1><i class="fas fa-credit-card"></i> Payment Dashboard</h1>
                <p>Kelola pembayaran pengiriman Anda dengan mudah</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-number">{{ $totalDeliveries }}</div>
                    <div class="stat-label">Total Pengiriman</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number">{{ $pendingPayments }}</div>
                    <div class="stat-label">Pembayaran Pending</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon paid">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">{{ $paidPayments }}</div>
                    <div class="stat-label">Sudah Dibayar</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon amount">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-number">Rp {{ number_format($totalAmount / 1000, 0) }}K</div>
                    <div class="stat-label">Total Dibayar</div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Pending Payments -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3><i class="fas fa-exclamation-triangle"></i> Pembayaran Pending</h3>
                        <a href="{{ route('payment.history', ['status' => 'pending']) }}" class="view-all-btn">
                            Lihat Semua
                        </a>
                    </div>

                    @if($pendingPaymentsList->count() > 0)
                    @foreach($pendingPaymentsList->take(3) as $payment)
                    <div class="payment-item">
                        <div class="payment-header">
                            <div>
                                <div class="payment-resi">{{ $payment->resi }}</div>
                                <div class="payment-date">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="payment-route">
                            <i class="fas fa-route"></i>
                            {{ $payment->fromCity->name }} → {{ $payment->toCity->name }}
                        </div>
                        <div class="payment-details">
                            <div class="payment-amount">Rp {{ number_format($payment->shipping_cost, 0, ',', '.') }}
                            </div>
                            <span class="payment-status status-pending">Pending</span>
                        </div>
                        <div class="action-buttons">
                            <a href="{{ route('payment.show', $payment->resi) }}" class="btn-pay">
                                <i class="fas fa-credit-card"></i> Bayar
                            </a>
                            <a href="{{ route('tracking') }}?resi={{ $payment->resi }}" class="btn-track">
                                <i class="fas fa-search"></i> Track
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <p>Tidak ada pembayaran pending</p>
                    </div>
                    @endif
                </div>

                <!-- Recent Payments -->
                <div class="content-card">
                    <div class="content-card-header">
                        <h3><i class="fas fa-history"></i> Pembayaran Terbaru</h3>
                        <a href="{{ route('payment.history') }}" class="view-all-btn">
                            Lihat Semua
                        </a>
                    </div>

                    @if($recentPayments->count() > 0)
                    @foreach($recentPayments->take(3) as $payment)
                    <div class="payment-item">
                        <div class="payment-header">
                            <div>
                                <div class="payment-resi">{{ $payment->resi }}</div>
                                <div class="payment-date">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="payment-route">
                            <i class="fas fa-route"></i>
                            {{ $payment->fromCity->name }} → {{ $payment->toCity->name }}
                        </div>
                        <div class="payment-details">
                            <div class="payment-amount">Rp {{ number_format($payment->shipping_cost, 0, ',', '.') }}
                            </div>
                            <span class="payment-status status-{{ $payment->payment_status }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </div>
                        @if($payment->payment_status === 'pending')
                        <div class="action-buttons">
                            <a href="{{ route('payment.show', $payment->resi) }}" class="btn-pay">
                                <i class="fas fa-credit-card"></i> Bayar
                            </a>
                            <a href="{{ route('tracking') }}?resi={{ $payment->resi }}" class="btn-track">
                                <i class="fas fa-search"></i> Track
                            </a>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        <i class="fas fa-receipt"></i>
                        <p>Belum ada pembayaran</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>