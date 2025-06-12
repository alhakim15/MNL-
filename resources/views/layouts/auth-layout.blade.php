<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Auth Pages' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Halaman autentikasi untuk aplikasi MyApp">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #e6f0ff;
            --secondary-color: #3f37c9;
            --text-color: #2b2d42;
            --light-text: #8d99ae;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: var(--text-color);
            line-height: 1.6;
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
        }

        .auth-illustration {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-illustration::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .auth-illustration::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .illustration-img {
            max-width: 80%;
            margin-bottom: 2rem;
            z-index: 1;
            animation: float 6s ease-in-out infinite;
        }

        .illustration-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            z-index: 1;
        }

        .illustration-text {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 80%;
            z-index: 1;
        }

        .auth-form {
            flex: 1;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .auth-logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 3rem;
            text-align: center;
        }

        .auth-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .auth-subtitle {
            color: var(--light-text);
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: var(--primary-light);
            color: var(--primary-color);
            border: none;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius) !important;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .auth-btn {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
        }

        .auth-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .auth-footer {
            text-align: center;
            color: var(--light-text);
            margin-top: 1rem;
        }

        .auth-footer a {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .invalid-feedback {
            font-size: 0.85rem;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-illustration {
                padding: 2rem;
            }

            .auth-form {
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-illustration">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/login-3305943-2757111.png"
                alt="Login Illustration" class="illustration-img">
            <h3 class="illustration-title">Selamat Datang</h3>
            <p class="illustration-text">Silakan login atau daftar untuk mengakses dashboard aplikasi</p>
        </div>

        <div class="auth-form">
            <div class="auth-logo">
                <i class="fas fa-cube me-2"></i>Mutiara Nasional Line
            </div>
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>