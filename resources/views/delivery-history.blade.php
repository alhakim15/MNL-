<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delivery History</title>
    <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .history-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Back Button */
        .back-button-container {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 10;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ff0044;
            color: white;
            text-decoration: none;
            padding: 14px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }

        .back-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .back-btn:hover::before {
            left: 100%;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #ff0044 0%, #ff0044 100%);
            transform: translateX(-5px) translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .back-btn:active {
            transform: translateX(-3px) translateY(-1px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .back-btn i {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .back-btn:hover i {
            transform: translateX(-3px);
        }

        .back-btn span {
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }


        .page-header {
            background: linear-gradient(135deg, #0a0f1d, #0a0f1d);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .delivery-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .delivery-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            padding: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .resi-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .resi-number {
            font-size: 1.2rem;
            font-weight: 700;
            color: #0a0f1d;
            font-family: 'Courier New', monospace;
        }

        .delivery-date {
            color: #64748b;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-shipped {
            background: #dbeafe;
            color: #0a0f1d;
        }

        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .card-body {
            padding: 20px;
        }

        .delivery-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0a0f1d;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 2px;
        }

        .detail-value {
            font-weight: 600;
            color: #1e293b;
        }

        .route-display {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            flex-wrap: wrap;
        }

        .route-city {
            font-weight: 600;
            color: #0a0f1d;
        }

        .route-arrow {
            color: #64748b;
        }

        .route-display>div:last-child {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #cbd5e1;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: #475569;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .track-button {
            background: #ff0044;
            color: white;
            border: none;
            padding: 8px 11px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.2s ease;
            display: inline-block;
        }

        .track-button:hover {
            background: #0a0f1d;
            color: white;
        }

        .payment-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-block;
            margin-left: 10px;
        }

        .payment-button:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .payment-status-container {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .text-success {
            color: #28a745 !important;
            font-weight: 600;
        }

        .text-warning {
            color: #ffc107 !important;
            font-weight: 600;
        }

        .text-danger {
            color: #dc3545 !important;
            font-weight: 600;
        }



        @media (max-width: 768px) {
            body {
                background: #ffffff !important;
            }

            body::before {
                display: none !important;
            }

            .delivery-details {
                grid-template-columns: 1fr;
            }

            .resi-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .route-display {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .route-display>div:last-child {
                margin-left: 0 !important;
                align-self: flex-start;
            }

            .track-button {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                min-width: 80px;
                height: 36px;
            }
        }
    </style>
</head>

<body>
    <div class="back-button-container">
        <a href="{{ route('home') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="history-container">
        <div class="page-header">
            <h1><i class="fas fa-history"></i> Delivery History</h1>
            <p>Riwayat pengiriman Anda</p>
        </div>

        @if($deliveries->count() > 0)
        @foreach($deliveries as $delivery)
        <div class="delivery-card">
            <div class="card-header">
                <div class="resi-info">
                    <div>
                        <div class="resi-number">{{ $delivery->resi }}</div>
                        <div class="delivery-date">
                            <i class="fas fa-calendar"></i>
                            {{ $delivery->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div>
                        @if($delivery->latestStatus)
                        @php
                        $statusClass = match($delivery->latestStatus->status) {
                        'pending' => 'status-pending',
                        'shipped' => 'status-shipped',
                        'delivered' => 'status-delivered',
                        'cancelled' => 'status-cancelled',
                        default => 'status-pending'
                        };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ ucfirst($delivery->latestStatus->status) }}
                        </span>
                        @else
                        <span class="status-badge status-pending">Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="route-display">
                    <span class="route-city">{{ $delivery->fromCity->name }}</span>
                    <i class="fas fa-arrow-right route-arrow"></i>
                    <span class="route-city">{{ $delivery->toCity->name }}</span>
                    <div style="margin-left: auto;">
                        <a href="{{ route('tracking') }}?resi={{ $delivery->resi }}" class="track-button">
                            <i class="fas fa-search"></i> Track
                        </a>
                    </div>
                </div>

                <div class="delivery-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Penerima</div>
                            <div class="detail-value">{{ $delivery->receiver_name }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Barang</div>
                            <div class="detail-value">{{ $delivery->item_name }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-weight-hanging"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Berat</div>
                            <div class="detail-value">{{ $delivery->weight }} ton</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-ship"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Kapal</div>
                            <div class="detail-value">{{ $delivery->ship->name }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Tanggal Kirim</div>
                            <div class="detail-value">{{ \Carbon\Carbon::parse($delivery->delivery_date)->format('d M
                                Y') }}</div>
                        </div>
                    </div>

                    @if($delivery->latestStatus)
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Lokasi Terakhir</div>
                            <div class="detail-value">{{ $delivery->latestStatus->location }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Biaya Pengiriman</div>
                            <div class="detail-value">Rp {{ number_format($delivery->shipping_cost, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Status Pembayaran</div>
                            <div class="detail-value">
                                @php
                                $paymentClass = match($delivery->payment_status) {
                                'paid' => 'text-success',
                                'pending' => 'text-warning',
                                'failed' => 'text-danger',
                                'expired' => 'text-danger',
                                default => 'text-warning'
                                };
                                @endphp
                                <div class="payment-status-container">
                                    <span class="{{ $paymentClass }}">
                                        @if($delivery->payment_status === 'paid')
                                        <i class="fas fa-check-circle"></i> Sudah Dibayar
                                        @elseif($delivery->payment_status === 'pending')
                                        <i class="fas fa-clock"></i> Menunggu Pembayaran
                                        @elseif($delivery->payment_status === 'failed')
                                        <i class="fas fa-times-circle"></i> Gagal
                                        @elseif($delivery->payment_status === 'expired')
                                        <i class="fas fa-exclamation-circle"></i> Kedaluwarsa
                                        @endif
                                    </span>
                                    @if($delivery->payment_status !== 'paid')
                                    <a href="{{ route('payment.show', $delivery->resi) }}" class="payment-button">
                                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="pagination-wrapper">
            {{ $deliveries->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-truck"></i>
            <h3>Belum Ada Pengiriman</h3>
            <p>Anda belum melakukan pengiriman apapun. Mulai kirim barang sekarang!</p>
            <a href="{{ route('deliveries.create') }}" class="track-button"
                style="margin-top: 20px; display: inline-block;">
                <i class="fas fa-plus"></i> Buat Pengiriman
            </a>
        </div>
        @endif
    </div>
</body>

</html>