<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Resi Pengiriman Kapal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(to right, #0077b6, #00b4d8);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            background: #023e8a;
        }

        .card-header h2,
        .card-header p {
            color: #ffffff;
        }

        .btn-primary {
            background-color: #0077b6;
            border-color: #0077b6;
        }

        .btn-primary:hover {
            background-color: #005f87;
        }

        .form-control:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 0 0.25rem rgba(0, 180, 216, 0.25);
        }

        .card-footer {
            background-color: #f1f1f1;
        }

        .input-group-text {
            background-color: #e3f2fd;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Card -->
                <div class="card shadow-lg">
                    <!-- Card Header -->
                    <div class="card-header text-white">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-ship fs-1 me-3"></i>
                            <div>
                                <h2 class="h4 mb-0">Lacak Pengiriman Anda</h2>
                                <p class="mb-0">Cek status paket yang dikirim via kapal laut</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4 bg-white">
                        @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form action="{{ route('tracking.search') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="resi" class="form-label">Nomor Resi Pengiriman</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="bi bi-upc-scan"></i>
                                    </span>
                                    <input type="text" class="form-control" id="resi" name="resi"
                                        placeholder="Contoh: TRKABCDEFGHIJ" required>
                                </div>
                                <div class="form-text">Masukkan nomor resi yang Anda terima setelah booking pengiriman.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="bi bi-search me-2"></i> Lacak Sekarang
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer text-center py-3">
                        <small class="text-muted">© {{ date('Y') }} Mutiara Nasional Line - Semua Hak Dilindungi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>