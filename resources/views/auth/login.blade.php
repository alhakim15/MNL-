<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Mutiara Nasional Line</title>
<link href="{{ asset('css/login.css') }}" rel="stylesheet" > 
</head>
<body>
  <div class="top-left">
    <img src="logo-mtix.png" alt="m-Tix Logo" class="logo" />
  </div>
  <div class="login-container">
    <div class="login-box">
      <h2>Hai, senang ketemu lagi!</h2>
      <form method="POST" action="index.php">
        <input type="text" name="username" placeholder="Nomor telepon" required />
        <div class="password-wrapper">
          <input type="password" name="password" placeholder="Masukkan PIN kamu" id="password" required />
          <span class="toggle" onclick="togglePassword()">👁</span>
        </div>
        <button type="submit" name="login">Login</button>
        <div class="small-links">
          <a href="#">Lupa PIN?</a>
        </div>
      </form>
      <p class="signup-text">Gak punya akun? <a href="register.php">Yuk, buat akun</a></p>
    </div>
  </div>
  <script>
    function togglePassword() {
      const pwd = document.getElementById("password");
      pwd.type = pwd.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>