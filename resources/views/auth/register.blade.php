<!-- filepath: /root/MNL-/resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Mutiara Nasional Line</title>
  <link rel="stylesheet" href="{{ asset('css/regis.css') }}">

</head>

<body>
  <div class="register-container">
    <img src="{{ asset('Gambar/kapal cargo.jpg') }}" alt="Logo" class="register-logo">
    <div class="register-title">Buat Akun Baru</div>
    <div class="register-subtitle">Isi formulir berikut untuk mendaftar</div>
    @if ($errors->any())
    <div class="register-error">
      <ul style="margin:0; padding-left:18px; text-align:left;">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <form method="POST" action="{{ route('register.submit') }}" class="register-form" autocomplete="off">
      @csrf
      <label class="form-label" for="first_name">Nama Depan</label>
      <input type="text" name="first_name" id="first_name" placeholder="Nama Depan" value="{{ old('first_name') }}"
        required>

      <label class="form-label" for="last_name">Nama Belakang</label>
      <input type="text" name="last_name" id="last_name" placeholder="Nama Belakang" value="{{ old('last_name') }}"
        required>

      <label class="form-label" for="reg_email">Alamat Email</label>
      <input type="email" name="email" id="reg_email" placeholder="email@contoh.com" value="{{ old('email') }}"
        required>

      <label class="form-label" for="reg_password">Kata Sandi</label>
      <div class="input-group">
        <input type="password" name="password" id="reg_password" placeholder="Password" required>
        <button class="toggle-password" type="button" tabindex="-1">
          <span class="fa fa-eye"></span>
        </button>
      </div>

      <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
      <div class="input-group">
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password"
          required>
        <button class="toggle-password" type="button" tabindex="-1">
          <span class="fa fa-eye"></span>
        </button>
      </div>

      <button type="submit" class="register-btn">Daftar Sekarang</button>
    </form>
    <div class="register-links">
      Sudah punya akun?
      <a href="{{ route('login') }}">Login</a>
    </div>
  </div>
  <script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
      button.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.innerHTML = type === 'password'
          ? '<span class="fa fa-eye"></span>'
          : '<span class="fa fa-eye-slash"></span>';
      });
    });
  </script>
  <!-- Font Awesome CDN for eye icon (optional, or use your own icon) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</body>

</html>