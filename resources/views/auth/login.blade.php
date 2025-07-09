<!-- filepath: /root/MNL-/resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Mutiara Nasional Line</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

<!-- Back Button -->
  <div class="back-button-container">
    <a href="{{ route('home') }}" class="back-btn">
      <i class="fas fa-arrow-left"></i>
      <span>Kembali</span>
    </a>
  </div>

  <div class="login-container">
    <div class="login-title">Login</div>

    <form method="POST" action="{{ route('login') }}" class="login-form">
      @csrf
      <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="login-btn">Masuk</button>
    </form>
    <div class="login-links">
      Belum punya akun?
      <a href="{{ route('register') }}">Daftar</a>
    </div>
  </div>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Handle Laravel session flash messages with SweetAlert2
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3085d6'
      });
    @endif

    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal!',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33'
      });
    @endif

    @if($errors->any())
      let errorMessages = '';
      @foreach($errors->all() as $error)
        errorMessages += '• {{ $error }}\n';
      @endforeach
      
      Swal.fire({
        icon: 'error',
        title: 'Terjadi Kesalahan!',
        text: errorMessages,
        confirmButtonText: 'OK',
        confirmButtonColor: '#d33'
      });
    @endif
  </script>
</body>

</html>