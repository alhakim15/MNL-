<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembayaran - Liners Shipping</title>
    <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .payment-dashboard {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
        }

        .dashboard-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            background: white;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-top {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-btn {
            color: #6c757d;
            font-size: 24px;
            margin-right: 16px;
            text-decoration: none;
        }

        .header-title {
            font-size: 20px;
            font-weight: 600;
            color: #212529;
            margin: 0;
        }

        .summary-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 0 20px 20px 20px;
            display: flex;
            justify-content: space-between;
        }

        .summary-item {
            text-align: center;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 4px;
        }

        .summary-label {
            font-size: 14px;
            color: #6c757d;
        }

        .filter-tabs {
            display: flex;
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 0 20px;
        }

        .filter-tab {
            flex: 1;
            text-align: center;
            padding: 16px 0;
            color: #6c757d;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-tab.active {
            color: #007bff;
            border-bottom-color: #007bff;
        }

        .search-filter-section {
            padding: 20px;
            background: white;
            border-bottom: 1px solid #e9ecef;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #007bff;
        }

        .filter-section {
            display: flex;
            gap: 16px;
            margin-top: 16px;
        }

        .filter-dropdown {
            flex: 1;
            padding: 12px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: white;
            font-size: 14px;
        }

        .payment-list {
            padding: 0 20px 20px 20px;
        }

        .payment-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
            transition: box-shadow 0.3s ease;
        }

        .payment-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .payment-info h4 {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
        }

        .payment-info .resi {
            font-size: 14px;
            color: #6c757d;
        }

        .payment-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-paid {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
        }

        .payment-details {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .payment-amount {
            font-size: 18px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 12px;
        }

        .payment-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            color: white;
        }

        .btn-outline {
            background: white;
            color: #6c757d;
            border: 1px solid #e9ecef;
        }

        .btn-outline:hover {
            background: #f8f9fa;
            color: #495057;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .summary-card {
                margin: 0 16px 16px 16px;
                padding: 16px;
            }

            .filter-tabs {
                padding: 0 16px;
            }

            .search-filter-section {
                padding: 16px;
            }

            .payment-list {
                padding: 0 16px 16px 16px;
            }

            .filter-section {
                flex-direction: column;
            }

            .payment-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="payment-dashboard">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="dashboard-header">
                <div class="header-top">
                    <a href="{{ route('home') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="header-title">Pembayaran</h1>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-card">
                <div class="summary-item">
                    <div class="summary-value">IDR {{ number_format($totalAmount, 0, ',', '.') }}</div>
                    <div class="summary-label">Jumlah Pembayaran</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value">{{ $paidPayments }}</div>
                    <div class="summary-label">Transaksi Berhasil</div>
                </div>
            </div>

            @if(($status ?? 'all') !== 'all')
            <div class="summary-card">
                <div class="summary-item">
                    <div class="summary-value">
                        @if($status == 'pending')
                        {{ $pendingPayments }}
                        @elseif($status == 'paid')
                        {{ $paidPayments }}
                        @elseif($status == 'failed')
                        {{ $failedPayments ?? 0 }}
                        @endif
                    </div>
                    <div class="summary-label">
                        @if($status == 'pending')
                        Pembayaran Menunggu
                        @elseif($status == 'paid')
                        Pembayaran Berhasil
                        @elseif($status == 'failed')
                        Pembayaran Gagal
                        @endif
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-value">{{ $recentPayments->count() }}</div>
                    <div class="summary-label">Total Item</div>
                </div>
            </div>
            @endif

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <a href="{{ route('payment.dashboard', ['status' => 'pending']) }}"
                    class="filter-tab {{ ($status ?? 'all') == 'pending' ? 'active' : '' }}">
                    Menunggu
                </a>
                <a href="{{ route('payment.dashboard', ['status' => 'paid']) }}"
                    class="filter-tab {{ ($status ?? 'all') == 'paid' ? 'active' : '' }}">
                    Terbayar
                </a>
                <a href="{{ route('payment.dashboard', ['status' => 'failed']) }}"
                    class="filter-tab {{ ($status ?? 'all') == 'failed' ? 'active' : '' }}">
                    Gagal
                </a>
                <a href="{{ route('payment.dashboard') }}"
                    class="filter-tab {{ ($status ?? 'all') == 'all' || !isset($status) ? 'active' : '' }}">
                    Riwayat
                </a>
            </div>

            <!-- Search and Filter -->
            <div class="search-filter-section">
                <input type="text" class="search-input" placeholder="Cari pengiriman kamu di sini" id="searchInput">
                <div class="filter-section">
                    <select class="filter-dropdown" id="productFilter">
                        <option value="">Produk</option>
                        <option value="shipping">Shipping Service</option>
                    </select>
                    <select class="filter-dropdown" id="statusFilter">
                        <option value="">Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                    </select>
                    <button class="btn btn-outline" onclick="clearFilters()">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>

            <!-- Payment List -->
            <div class="payment-list">
                @if($recentPayments->count() > 0)
                @foreach($recentPayments as $payment)
                <div class="payment-item" data-status="{{ $payment->payment_status }}"
                    data-item="{{ strtolower($payment->item_name) }}">
                    <div class="payment-header">
                        <div class="payment-info">
                            <h4>{{ $payment->item_name }}</h4>
                            <div class="resi">{{ $payment->resi }}</div>
                        </div>
                        <span class="payment-status status-{{ $payment->payment_status }}">
                            @if($payment->payment_status === 'paid')
                            Berhasil
                            @elseif($payment->payment_status === 'pending')
                            Menunggu
                            @else
                            {{ ucfirst($payment->payment_status) }}
                            @endif
                        </span>
                    </div>

                    <div class="payment-details">
                        <i class="fas fa-route"></i>
                        {{ $payment->fromCity->name }} → {{ $payment->toCity->name }} •
                        {{ $payment->weight }} ton •
                        {{ $payment->created_at->format('d M Y') }}
                    </div>

                    <div class="payment-amount">
                        Rp {{ number_format($payment->shipping_cost, 0, ',', '.') }}
                    </div>

                    @if($payment->payment_status === 'pending')
                    <div class="payment-actions">
                        <a href="{{ route('payment.show', $payment->resi) }}" class="btn btn-primary">
                            <i class="fas fa-credit-card"></i> Bayar Sekarang
                        </a>
                        <a href="{{ route('tracking') }}?resi={{ $payment->resi }}" class="btn btn-outline">
                            <i class="fas fa-search"></i> Lacak
                        </a>
                    </div>
                    @else
                    <div class="payment-actions">
                        <a href="{{ route('tracking') }}?resi={{ $payment->resi }}" class="btn btn-outline">
                            <i class="fas fa-search"></i> Lacak Pengiriman
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
                @else
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <h3>Belum ada daftar transaksi</h3>
                    <p>Mulai dengan membuat pengiriman baru</p>
                    <br>
                    <a href="{{ route('deliveries.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Pengiriman Baru
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.payment-item');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Filter functionality
        function filterItems() {
            const statusFilter = document.getElementById('statusFilter').value;
            const productFilter = document.getElementById('productFilter').value;
            const items = document.querySelectorAll('.payment-item');
            
            items.forEach(item => {
                let showItem = true;
                
                if (statusFilter && item.dataset.status !== statusFilter) {
                    showItem = false;
                }
                
                if (productFilter && !item.dataset.item.includes(productFilter)) {
                    showItem = false;
                }
                
                item.style.display = showItem ? 'block' : 'none';
            });
        }

        function clearFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('productFilter').value = '';
            document.getElementById('searchInput').value = '';
            
            const items = document.querySelectorAll('.payment-item');
            items.forEach(item => {
                item.style.display = 'block';
            });
        }

        document.getElementById('statusFilter').addEventListener('change', filterItems);
        document.getElementById('productFilter').addEventListener('change', filterItems);

        // Add visual feedback for filter tabs
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = document.querySelector('.filter-tab.active');
            if (activeTab) {
                activeTab.style.backgroundColor = '#f8f9fa';
            }
        });

        // Add counter badges to tabs
        @if(isset($pendingPayments, $paidPayments, $failedPayments))
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.filter-tab');
            tabs.forEach(tab => {
                const href = tab.getAttribute('href');
                let count = 0;
                
                if (href.includes('status=pending')) {
                    count = {{ $pendingPayments }};
                } else if (href.includes('status=paid')) {
                    count = {{ $paidPayments }};
                } else if (href.includes('status=failed')) {
                    count = {{ $failedPayments ?? 0 }};
                } else {
                    count = {{ $totalDeliveries }};
                }
                
                if (count > 0) {
                    const badge = document.createElement('span');
                    badge.style.cssText = 'background: #dc3545; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; margin-left: 5px;';
                    badge.textContent = count;
                    tab.appendChild(badge);
                }
            });
        });
        @endif
    </script>
</body>

</html>