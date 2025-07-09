<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lacak Resi Pengiriman Kapal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0f111c;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }

        .card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 24px rgba(26, 35, 126, 0.25);
            border: 3px solid #ff0044; /* 👉 Tambahkan stroke di sini */
        }

        .card-header {
            background:#0f1323;
        }

        .card-header h2,
        .card-header p {
            color: #fff;
        }

        .btn-primary {
            background-color: #ff0044;
            border-color: #ff0044;
        }

        .btn-primary:hover {
            background-color: #c2185b;
            border-color: #c2185b;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .form-control:focus {
            border-color: #e91e63;
            box-shadow: 0 0 0 0.25rem rgba(233, 30, 99, 0.25);
        }

        .card-footer {
            background-color: #f9f9f9;
        }

        .input-group-text {
            background-color: #fff0f4;
            color: #e91e63;
        }

        .form-label {
            color: #333;
            font-weight: 600;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #388e3c;
        }

        .alert-danger {
            background-color: #fdecea;
            color: #d32f2f;
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
  background: linear-gradient(135deg, #ff0044 0%, #ff0044 100%);
  color: white;
  text-decoration: none;
  padding: 14px 24px;
  border-radius: 50px;
  font-size: 14px;
  font-weight: 600;
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255, 255, 255, 0.2);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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


        @media (max-width: 576px) {
            .card-header h2 {
                font-size: 1.25rem;
            }

            .card-header p {
                font-size: 0.9rem;
            }

            .btn {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                    <div class="back-button-container">
                        <a href="{{ route('home') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                        </a>
                    </div>
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
                                        placeholder="Contoh: TRKABCDEFGHIJ" value="{{ $resi ?? '' }}" required>
                                </div>
                                <div class="form-text">Masukkan nomor resi yang Anda terima setelah booking pengiriman.
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="bi bi-search me-2"></i> Lacak Sekarang
                                </button>
                            </div>

                            <!-- Tombol Kembali -->
                            <div class="d-grid">
                                <a href="{{ url('/') }}" class="btn btn-secondary btn-lg shadow-sm">
                                    <i class="bi bi-arrow-left-circle me-2"></i> Kembali ke Halaman Awal
                                </a>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>