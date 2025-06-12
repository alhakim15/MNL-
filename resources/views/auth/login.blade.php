@extends('layouts.auth-layout')

@section('content')
<h2 class="auth-title">Masuk ke Akun Anda</h2>
<p class="auth-subtitle">Masukkan email dan kata sandi Anda untuk melanjutkan</p>

<form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
  @csrf

  <div class="mb-3">
    <label for="email" class="form-label">Alamat Email</label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
      <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
        placeholder="email@contoh.com" value="{{ old('email') }}" required autofocus>
      <div class="invalid-feedback">
        @error('email') {{ $message }} @else Harap masukkan email yang valid @enderror
      </div>
    </div>
  </div>

  <div class="mb-3">
    <label for="password" class="form-label">Kata Sandi</label>
    <div class="input-group">
      <span class="input-group-text"><i class="fas fa-lock"></i></span>
      <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
        placeholder="••••••••" required>
      <button class="btn btn-outline-secondary toggle-password" type="button">
        <i class="fas fa-eye"></i>
      </button>
      <div class="invalid-feedback">
        @error('password') {{ $message }} @else Harap masukkan kata sandi Anda @enderror
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="remember" id="remember">
      <label class="form-check-label" for="remember">Ingat Saya</label>
    </div>
    {{-- <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--primary-color);">
      Lupa Password?
    </a> --}}
  </div>

  <button type="submit" class="btn auth-btn w-100 text-white mb-3 py-2 fw-bold">
    <i class="fas fa-sign-in-alt me-2"></i> Masuk
  </button>


  <p class="auth-footer">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
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