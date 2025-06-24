<!-- filepath: /root/MNL-/resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Mutiara Nasional Line</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    
</head>

<body>
  <div class="login-container">
    <img src="{{ asset('Gambar/kapal cargo.jpg') }}" alt="Logo" class="login-logo">
    <div class="login-title">Login</div>
    @if(session('error'))
    <div class="login-error">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="login-form">
      @csrf
      <input type="email" name="email" placeholder="Email" required autofocus>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="login-btn">Masuk</button>
    </form>
    <div class="login-links">
      Belum punya akun?
      <a href="{{ route('register') }}">Daftar</a>
    </div>
  </div>
</body>

</html>