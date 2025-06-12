<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Kami</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h2>Selamat Datang Kembali</h2>
        <p>Silakan masuk ke akun Anda</p>
      </div>

      @if($errors->any())
      <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="form-options">
          <label class="remember-me">
            <input type="checkbox" name="remember"> Ingat Saya
          </label>
          {{-- <a href="{{ route('password.request') }}" class="forgot-password">Lupa Password?</a> --}}
        </div>

        <button type="submit" class="auth-button">Masuk</button>
      </form>

      <div class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar disini</a>
      </div>
    </div>

    <div class="auth-branding">
      <img src="{{ asset('Gambar/fix.png') }}" alt="Logo Perusahaan">
      <h1>Sistem Kami</h1>
      <p>Solusi terbaik untuk kebutuhan bisnis Anda</p>
    </div>
  </div>
</body>

</html>