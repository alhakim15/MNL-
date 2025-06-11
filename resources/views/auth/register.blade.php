<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun m.tix</title>
<link href="{{ asset('css/register.css') }}" rel="stylesheet" > 
</head>

<body>
  <div class="top-left">
    <img src="logo-mtix.png" alt="m-Tix Logo" class="logo" />
  </div>
  <div class="container">
  <header>
    <span class="back-arrow">&#8592;</span>
    <h1>Buat akun m.tix kamu</h1>
  </header>

  <form class="form-box" method="POST" action="login(1).php">
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />

    <div class="actions">
      <button type="submit" name="register">Daftar</button>
      <a href="login.php" class="secondary-btn">Login</a>
    </div>
  </form>
</div>

  </div>
</body>
</html>