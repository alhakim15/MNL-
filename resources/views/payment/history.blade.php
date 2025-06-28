<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment History - Liners Shipping</title>
    <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-history {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .history-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .history-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .history-header h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .history-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .filter-tab.active,
        .filter-tab:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
        }

        .payment-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .payment-card:hover {
            transform: translateY(-5px);
        }

        .payment-card-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .payment-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .payment-meta {
            color: white;
        }

        .payment-resi {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .payment-date {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .payment-status {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
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

        .status-expired {
            background: rgba(156, 163, 175, 0.2);
            color: #9ca3af;
            border: 1px solid rgba(156, 163, 175, 0.3);
        }

        .payment-card-body {
            padding: 20px;
        }

        .payment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
            margin-bottom: 2px;
        }

        .detail-value {
            color: white;
            font-weight: 600;
        }

        .payment-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4ade80;
            text-align: center;
            padding: 15px;
            background: rgba(74, 222, 128, 0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .payment-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-pay {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
            color: white;
        }

        .btn-track {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-track:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .empty-state i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: white;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            padding: 10px 15px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination a:hover,
        .pagination .active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        @media (max-width: 768px) {
            .payment-card-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .payment-details {
                grid-template-columns: 1fr;
            }

            .payment-actions {
                flex-direction: column;
            }

            .filter-tabs {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="payment-history">
        <nav class="back-nav">
            <a href="{{ route('payment.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('home') }}" class="back-button">
                <i class="fas fa-home"></i> Home
            </a>
        </nav>

        <div class="history-container">
            <div class="history-header">
                <h1><i class="fas fa-history"></i> Payment History</h1>
                <p>Riwayat pembayaran pengiriman Anda</p>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <a href="{{ route('payment.history', ['status' => 'all']) }}"
                    class="filter-tab {{ $status === 'all' ? 'active' : '' }}">
                    <i class="fas fa-list"></i> Semua
                </a>
                <a href="{{ route('payment.history', ['status' => 'pending']) }}"
                    class="filter-tab {{ $status === 'pending' ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Pending
                </a>
                <a href="{{ route('payment.history', ['status' => 'paid']) }}"
                    class="filter-tab {{ $status === 'paid' ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> Paid
                </a>
                <a href="{{ route('payment.history', ['status' => 'failed']) }}"
                    class="filter-tab {{ $status === 'failed' ? 'active' : '' }}">
                    <i class="fas fa-times-circle"></i> Failed
                </a>
            </div>

            <!-- Payment Cards -->
            @if($payments->count() > 0)
            @foreach($payments as $payment)
            <div class="payment-card">
                <div class="payment-card-header">
                    <div class="payment-info">
                        <div class="payment-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="payment-meta">
                            <div class="payment-resi">{{ $payment->resi }}</div>
                            <div class="payment-date">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    <span class="payment-status status-{{ $payment->payment_status }}">
                        {{ ucfirst($payment->payment_status) }}
                    </span>
                </div>

                <div class="payment-card-body">
                    <div class="payment-amount">
                        Rp {{ number_format($payment->shipping_cost, 0, ',', '.') }}
                    </div>

                    <div class="payment-details">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Penerima</div>
                                <div class="detail-value">{{ $payment->receiver_name }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Rute</div>
                                <div class="detail-value">{{ $payment->fromCity->name }} → {{ $payment->toCity->name }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Barang</div>
                                <div class="detail-value">{{ $payment->item_name }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-weight-hanging"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Berat</div>
                                <div class="detail-value">{{ $payment->weight }} ton</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-ship"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Kapal</div>
                                <div class="detail-value">{{ $payment->ship->name }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Tanggal Kirim</div>
                                <div class="detail-value">{{ $payment->delivery_date->format('d M Y') }}</div>
                            </div>
                        </div>

                        @if($payment->paid_at)
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Dibayar Pada</div>
                                <div class="detail-value">{{ $payment->paid_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        @endif

                        @if($payment->payment_type)
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Metode Bayar</div>
                                <div class="detail-value">{{ ucfirst($payment->payment_type) }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="payment-actions">
                        @if($payment->payment_status === 'pending')
                        <a href="{{ route('payment.show', $payment->resi) }}" class="btn btn-pay">
                            <i class="fas fa-credit-card"></i> Bayar Sekarang
                        </a>
                        @endif
                        <a href="{{ route('tracking') }}?resi={{ $payment->resi }}" class="btn btn-track">
                            <i class="fas fa-search"></i> Track Pengiriman
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $payments->appends(['status' => $status])->links('pagination::simple-bootstrap-4') }}
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <h3>Belum Ada Pembayaran</h3>
                <p>Anda belum memiliki riwayat pembayaran untuk status "{{ ucfirst($status) }}"</p>
                <a href="{{ route('deliverbook.create') }}" class="btn btn-pay">
                    <i class="fas fa-plus"></i> Buat Pengiriman Baru
                </a>
            </div>
            @endif
        </div>
    </div>
</body>

</html>