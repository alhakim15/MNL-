<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Liners Shipping</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            max-width: 480px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            animation: checkmark 0.8s ease-in-out 0.3s both;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .success-message {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .payment-details {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        .detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
        }

        .resi-value {
            font-family: 'Courier New', monospace;
            background: #e5e7eb;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
        }

        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #059669;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        @media (max-width: 480px) {
            .success-container {
                padding: 30px 20px;
                margin: 0 10px;
            }

            .success-icon {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }

            .success-title {
                font-size: 24px;
            }

            .success-message {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <div class="success-badge">
            <i class="fas fa-shield-alt"></i>
            Pembayaran Terverifikasi
        </div>

        <h1 class="success-title">Pembayaran Berhasil!</h1>

        <p class="success-message">
            Terima kasih! Pembayaran Anda telah berhasil diproses.
            Pengiriman akan segera dipersiapkan dan kami akan mengirimkan notifikasi update status.
        </p>

        @if(isset($delivery))
        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">No. Resi</span>
                <span class="detail-value resi-value">{{ $delivery->resi }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Pengirim</span>
                <span class="detail-value">{{ $delivery->sender_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tujuan</span>
                <span class="detail-value">{{ $delivery->recipient_name }}, {{ $delivery->toCity->name ?? 'N/A'
                    }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Bayar</span>
                <span class="detail-value">Rp {{ number_format($delivery->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value" style="color: #059669;">{{ ucfirst($delivery->payment_status) }}</span>
            </div>
            @if($delivery->payment_type)
            <div class="detail-row">
                <span class="detail-label">Metode Bayar</span>
                <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $delivery->payment_type)) }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Kembali ke Beranda
            </a>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                <i class="fas fa-sign-in-alt"></i>
                Login untuk Melihat Dashboard
            </a>
        </div>
    </div>
</body>

</html>