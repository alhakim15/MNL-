<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Mutiara Nasional Line</title>
    <link rel="stylesheet" href="{{ asset('css/regis.css') }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="register-container">
        <div class="register-title">Verifikasi Email Anda</div>
        <div class="register-subtitle">
            Kami telah mengirim link verifikasi ke email Anda: <strong>{{ auth()->user()->email }}</strong>
        </div>

        <div class="verification-info">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Silakan periksa email Anda dan klik link verifikasi untuk mengaktifkan akun Anda.
            </div>

            <div class="verification-status">
                @if (session('message'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('message') }}
                </div>
                @endif
            </div>

            <form method="POST" action="{{ route('verification.send') }}" class="resend-form">
                @csrf
                <p class="text-center">
                    Tidak menerima email?
                </p>
                <button type="submit" class="register-btn">
                    <i class="bi bi-envelope me-2"></i>
                    Kirim Ulang Link Verifikasi
                </button>
            </form>

            <div class="register-links">
                <a href="{{ route('home') }}">Kembali ke Beranda</a>
                <span class="mx-2">|</span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit"
                        style="background: none; border: none; color: #e91e63; text-decoration: underline; cursor: pointer;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif

        @if(session('message'))
            Swal.fire({
                icon: 'success',
                title: 'Email Terkirim!',
                text: '{{ session('message') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
        @endif
    </script>

    <style>
        .verification-info {
            text-align: left;
            margin: 2rem 0;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-info {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid #bbdefb;
        }

        .alert-success {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .resend-form {
            text-align: center;
            margin: 1.5rem 0;
        }

        .resend-form p {
            margin-bottom: 1rem;
            color: #666;
        }

        .register-links {
            margin-top: 2rem;
            text-align: center;
        }

        .register-links a {
            color: #e91e63;
            text-decoration: none;
        }

        .register-links a:hover {
            text-decoration: underline;
        }
    </style>
</body>

</html>