<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Payment - Liners Shipping</title>
    <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <style>
        body {
            background: #ffffff !important;
        }

        body::before {
            display: none !important;
        }

        .main-container {
            background: #ffffff;
            min-height: 100vh;
            padding: 20px;
        }

        .payment-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .payment-header h2 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .payment-header .subtitle {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .delivery-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .delivery-info h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .info-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.1rem;
            color: #27ae60;
        }

        .info-label {
            color: #6c757d;
            font-weight: 500;
        }

        .info-value {
            color: #2c3e50;
            font-weight: 600;
        }

        .payment-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #6c757d;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .payment-status {
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-failed {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Mobile responsiveness improvements */
        @media (max-width: 768px) {
            .main-container {
                padding: 10px;
            }

            .payment-container {
                margin: 1rem auto;
                padding: 1.5rem;
                border-radius: 10px;
            }

            .payment-header h2 {
                font-size: 1.5rem;
            }

            .payment-actions {
                flex-direction: column;
                gap: 0.8rem;
            }

            .btn {
                padding: 14px 20px;
                font-size: 16px;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <nav class="back-nav">
        <a href="{{ route('deliveries.history') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to History
        </a>
    </nav>

    <div class="main-container">
        <div class="payment-container">
            <div class="payment-header">
                <h2><i class="fas fa-credit-card"></i> Pembayaran Pengiriman</h2>
                <div class="subtitle">Resi: {{ $delivery->resi }}</div>
            </div>

            @if($delivery->payment_status === 'paid')
            <div class="payment-status status-paid">
                <i class="fas fa-check-circle"></i> Pembayaran sudah selesai
            </div>
            @elseif($delivery->payment_status === 'failed')
            <div class="payment-status status-failed">
                <i class="fas fa-times-circle"></i> Pembayaran gagal
            </div>
            @else
            <div class="payment-status status-pending">
                <i class="fas fa-clock"></i> Menunggu pembayaran
            </div>
            @endif

            <div class="delivery-info">
                <h3><i class="fas fa-shipping-fast"></i> Detail Pengiriman</h3>

                <div class="info-row">
                    <span class="info-label">Pengirim:</span>
                    <span class="info-value">{{ $delivery->sender_name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Penerima:</span>
                    <span class="info-value">{{ $delivery->receiver_name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Rute:</span>
                    <span class="info-value">{{ $delivery->fromCity->name }} → {{ $delivery->toCity->name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Barang:</span>
                    <span class="info-value">{{ $delivery->item_name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Berat:</span>
                    <span class="info-value">{{ $delivery->weight }} ton</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Kapal:</span>
                    <span class="info-value">{{ $delivery->ship->name }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Tanggal Pengiriman:</span>
                    <span class="info-value">{{ $delivery->delivery_date->format('d M Y') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Biaya Pengiriman:</span>
                    <span class="info-value">Rp {{ number_format($delivery->shipping_cost, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="payment-actions">
                @if($delivery->payment_status !== 'paid')
                <button id="pay-button" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                </button>
                <a href="{{ route('payment.check-status', $delivery->resi) }}" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> Check Status
                </a>
                @endif

                <a href="{{ route('payment.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        @if($delivery->payment_status !== 'paid' && $delivery->payment_token)
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $delivery->payment_token }}', {
                onSuccess: function(result){
                    window.location.href = "{{ route('payment.finish') }}?order_id={{ $delivery->resi }}";
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!");
                    console.log(result);
                },
                onError: function(result){
                    window.location.href = "{{ route('payment.error') }}";
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        };
        @endif
    </script>
</body>

</html>