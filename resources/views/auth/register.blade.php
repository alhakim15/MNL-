<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - Sistem Kami</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
  <div class="auth-container">
    <div class="auth-card">
      <div class="auth-header">
        <h2>Buat Akun Baru</h2>
        <p>Isi formulir berikut untuk mendaftar</p>
      </div>

      @if($errors->any())
      <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
      </div>
      @endif

      <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="auth-button">Daftar</button>
      </form>

      <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk disini</a>
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