<!-- filepath: /root/MNL-/resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Mutiara Nasional Line</title>
  <link rel="stylesheet" href="{{ asset('css/regis.css') }}">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>

<body>
  <div class="register-container">
    <div class="register-title">Buat Akun Baru</div>
    <div class="register-subtitle">Isi formulir berikut untuk mendaftar</div>

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