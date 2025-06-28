<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Pengiriman Kapal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #0f111c;
            min-height: 100vh;
            padding-top: 30px;
            padding-bottom: 30px;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(26, 35, 126, 0.25);
        }

        .card-header {
            background: #1a237e;
            color: #fff;
        }

        .card-header h1,
        .card-header p {
            color: #fff;
            margin-bottom: 4px;
        }

        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #cfd8dc;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-dot {
            position: absolute;
            left: -1.5rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid #e91e63;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-dot i {
            font-size: 0.6rem;
            color: #e91e63;
        }

        .timeline-dot.inactive {
            border-color: #bbb;
        }

        .timeline-dot.inactive i {
            color: #bbb;
        }

        .card-body h5,
        .card-body p,
        .card-body ul li {
            color: #333;
        }

        .card-body h5 i {
            color: #e91e63;
        }

        .card-footer {
            background-color: #f9f9f9;
            font-size: 0.9rem;
        }

        .alert-info {
            background-color: #fff3f6;
            color: #e91e63;
            border-color: #fce4ec;
        }

        .text-primary,
        .bi-flag-fill,
        .bi-ship,
        .bi-geo-fill {
            color: #1a237e !important;
        }

        .bg-primary {
            background-color: #1a237e !important;
        }

        .card .card {
            border: 1px solid #eee;
        }

        .card .card-body small {
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card shadow-lg">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-ship fs-3 me-3"></i>
                            <div>
                                <h1 class="h5 mb-0">Tracking Pengiriman Kapal</h1>
                                <p class="mb-0">Resi: <strong>{{ $delivery->resi }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body bg-white">
                        <div class="row mb-4">
                            <!-- Informasi Pengiriman -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="text-muted mb-3"><i class="bi bi-box-seam me-2"></i>Detail Barang
                                        </h5>
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>Barang:</strong> {{ $delivery->item_name }}</li>
                                            <li><strong>Pengirim:</strong> {{ $delivery->sender_name }}</li>
                                            <li><strong>Penerima:</strong> {{ $delivery->receiver_name }}</li>
                                            <li><strong>Berat:</strong> {{ $delivery->weight }} kg</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Rute -->
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="text-muted mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Rute
                                            Pengiriman</h5>
                                        <p><i class="bi bi-flag-fill text-primary me-2"></i><strong>Asal:</strong> {{
                                            $delivery->fromCity->name }}</p>
                                        <p><i class="bi bi-ship text-primary me-2"></i><strong>Kapal:</strong> {{
                                            $delivery->ship->name }}</p>
                                        <p><i class="bi bi-geo-fill text-primary me-2"></i><strong>Tujuan:</strong> {{
                                            $delivery->toCity->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <h5 class="mb-3"><i class="bi bi-clock-history me-2"></i>Riwayat Pengiriman</h5>

                        @if ($logs->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>Belum ada riwayat status untuk resi ini.
                        </div>
                        @else
                        <div class="timeline">
                            @foreach ($logs as $index => $log)
                            <div class="timeline-item">
                                <div class="timeline-dot {{ $index > 0 ? 'inactive' : '' }}">
                                    <i class="bi bi-circle-fill"></i>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1">{{ $log->status }}</h6>
                                            <small class="text-muted">{{ $log->logged_at->format('d M Y H:i') }}</small>
                                        </div>
                                        @if ($log->location)
                                        <p class="mb-1 text-dark"><i class="bi bi-geo-alt me-1"></i>{{ $log->location }}
                                        </p>
                                        @endif
                                        @if ($log->note)
                                        <p class="mb-0 text-muted"><i class="bi bi-chat-left-text me-1"></i>{{
                                            $log->note }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Tombol Kembali -->
                        <div class="text-center mt-4">
                            <a href="{{ url('/') }}" class="btn btn-secondary btn-lg shadow-sm">
                                <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Halaman Awal
                            </a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer text-center bg-light">
                        <small class="text-muted">© {{ date('Y') }} Mutiara Nasional Line • Untuk bantuan, hubungi
                            CS</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>