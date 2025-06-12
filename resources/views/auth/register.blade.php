@extends('layouts.auth-layout')

@section('content')
<h2 class="auth-title">Buat Akun Baru</h2>
<p class="auth-subtitle">Isi formulir berikut untuk mendaftar</p>

<form method="POST" action="{{ route('register.submit') }}" class="needs-validation" novalidate>
  @csrf

  <div class="row g-3">
    <div class="col-md-6">
      <label for="first_name" class="form-label">Nama Depan</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" name="first_name" id="first_name"
          class="form-control @error('first_name') is-invalid @enderror" placeholder="John"
          value="{{ old('first_name') }}" required>
        <div class="invalid-feedback">
          @error('first_name') {{ $message }} @else Harap masukkan nama depan Anda @enderror
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <label for="last_name" class="form-label">Nama Belakang</label>
      <div class="input-group">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror"
          placeholder="Doe" value="{{ old('last_name') }}" required>
        <div class="invalid-feedback">
          @error('last_name') {{ $message }} @else Harap masukkan nama belakang Anda @enderror
        </div>
      </div>
    </div>
  </div>

  <div class="mb-3 mt-3">
    <label for="reg_email" class="form-label">Alamat Email</label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
      <input type="email" name="email" id="reg_email" class="form-control @error('email') is-invalid @enderror"
        placeholder="email@contoh.com" value="{{ old('email') }}" required>
      <div class="invalid-feedback">
        @error('email') {{ $message }} @else Harap masukkan email yang valid @enderror
      </div>
    </div>
  </div>

  <div class="mb-3">
    <label for="reg_password" class="form-label">Kata Sandi</label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-lock"></i></span>
      <input type="password" name="password" id="reg_password"
        class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
      <button class="btn btn-outline-secondary toggle-password" type="button">
        <i class="fas fa-eye"></i>
      </button>
      <div class="invalid-feedback">
        @error('password') {{ $message }} @else Kata sandi minimal 8 karakter @enderror
      </div>
    </div>
    <div class="form-text">Gunakan minimal 8 karakter dengan kombinasi huruf dan angka</div>
  </div>

  <div class="mb-4">
    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-lock"></i></span>
      <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
        placeholder="••••••••" required>
      <button class="btn btn-outline-secondary toggle-password" type="button">
        <i class="fas fa-eye"></i>
      </button>
    </div>
  </div>

  <div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" id="terms" required>
    <label class="form-check-label" for="terms">
      Saya menyetujui <a href="#" style="color: var(--primary-color);">Syarat dan Ketentuan</a> serta <a href="#"
        style="color: var(--primary-color);">Kebijakan Privasi</a>
    </label>
  </div>

  <button type="submit" class="btn auth-btn w-100 text-white mb-3 py-2 fw-bold">
    <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
  </button>

  <p class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
</form>

<script>
  // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    });

    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            const forms = document.getElementsByClassName('needs-validation');
            Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
@endsection