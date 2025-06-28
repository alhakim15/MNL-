<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tidak Ditemukan - Liners Shipping</title>
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

        .not-found-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .not-found-content {
            max-width: 480px;
            width: 100%;
            background: white;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .not-found-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
        }

        .not-found-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 16px;
        }

        .not-found-message {
            font-size: 16px;
            color: #718096;
            line-height: 1.6;
            margin-bottom: 32px;
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
            transition: all 0.2s ease;
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
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }

        .status-info {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .status-info-content {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
        }

        .status-icon {
            width: 40px;
            height: 40px;
            background: #0ea5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .status-text {
            flex: 1;
        }

        .status-label {
            font-size: 14px;
            color: #0369a1;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .status-value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .not-found-content {
                padding: 30px 20px;
                margin: 0 10px;
            }

            .not-found-icon {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }

            .not-found-title {
                font-size: 20px;
            }

            .not-found-message {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="not-found-container">
        <div class="not-found-content">
            <div class="not-found-icon">
                <i class="fas fa-search"></i>
            </div>

            <h1 class="not-found-title">Pembayaran Tidak Ditemukan</h1>

            @if(isset($delivery))
            @if($delivery->payment_status === 'paid')
            <div class="status-info">
                <div class="status-info-content">
                    <div class="status-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="status-text">
                        <div class="status-label">Status Pembayaran</div>
                        <div class="status-value">Sudah Dibayar</div>
                    </div>
                </div>
            </div>

            <p class="not-found-message">
                Pembayaran untuk pengiriman <strong>{{ $delivery->resi }}</strong> sudah berhasil diselesaikan
                sebelumnya.
                Anda dapat melihat detail pembayaran di dashboard atau menghubungi customer service jika ada pertanyaan.
            </p>
            @elseif($delivery->payment_status === 'failed')
            <div class="status-info" style="background: #fef2f2; border-color: #fecaca;">
                <div class="status-info-content">
                    <div class="status-icon" style="background: #ef4444;">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="status-text">
                        <div class="status-label" style="color: #dc2626;">Status Pembayaran</div>
                        <div class="status-value">Gagal</div>
                    </div>
                </div>
            </div>

            <p class="not-found-message">
                Pembayaran untuk pengiriman <strong>{{ $delivery->resi }}</strong> mengalami kegagalan.
                Silakan coba lakukan pembayaran ulang atau hubungi customer service untuk bantuan.
            </p>
            @else
            <div class="status-info" style="background: #fefbf3; border-color: #fed7aa;">
                <div class="status-info-content">
                    <div class="status-icon" style="background: #f59e0b;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="status-text">
                        <div class="status-label" style="color: #d97706;">Status Pembayaran</div>
                        <div class="status-value">{{ ucfirst($delivery->payment_status) }}</div>
                    </div>
                </div>
            </div>

            <p class="not-found-message">
                Pembayaran untuk pengiriman <strong>{{ $delivery->resi }}</strong> sedang dalam proses atau belum
                diselesaikan.
                Silakan periksa kembali status pembayaran atau lakukan pembayaran jika belum.
            </p>
            @endif
            @else
            <p class="not-found-message">
                Maaf, data pembayaran yang Anda cari tidak ditemukan atau sudah tidak tersedia.
                Silakan periksa kembali atau hubungi customer service untuk bantuan.
            </p>
            @endif

            <div class="action-buttons">
                @auth
                @if(isset($delivery))
                @if($delivery->payment_status === 'failed' || $delivery->payment_status === 'pending')
                <a href="{{ route('payment.show', $delivery->resi) }}" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i>
                    Bayar Sekarang
                </a>
                @endif
                <a href="{{ route('payment.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-tachometer-alt"></i>
                    Lihat Dashboard Pembayaran
                </a>
                @else
                <a href="{{ route('payment.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i>
                    Lihat Dashboard Pembayaran
                </a>
                @endif
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Login untuk Melihat Pembayaran
                </a>
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
                @endauth
            </div>
        </div>
    </div>
</body>

</html>