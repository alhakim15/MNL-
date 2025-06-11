<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mutiara Nasional line</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" > 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poiret+One&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navbar Section -->
    <header class="navbar">
        <div class="navbar-container">
            <h1 class="logo">Mutiara Nasional Line</h1>
            <nav>
                <ul>
                    <li><a href="aboutus.php">About Us</a></li>
                    <li><a href="contactus.php">Contact Us</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section with Image -->
<section class="hero">
  <div class="hero-text">
    <h2>PENGIRIMAN CEPAT<br>ANTAR PONTIANAK<br>DAN BENGKULU</h2>
    <div class="button-group">
        <button type="button" class="search-btn search-btn-left"onclick="window.location.href='LearnMore.php'">LEARN MORE</button>
    </div>
  </div>
  <img src="Gambar/kapal cargo.jpg" alt="Modern House" class="hero-image">
</section>

<section id="explore" class="explore">
  <div class="carousel-wrapper">
    <div class="carousel-item">
      <a href="deliverbook.php">
        <img src="Gambar/Tugu Khatulistiwa.jpg" alt="Pontianak">
      </a>
    </div>
    <div class="carousel-item">
      <a href="deliverbook.php">
        <img src="Gambar/Icon Bengkulu.jpg" alt="Bengkulu">
      </a>
    </div>
    <div class="carousel-item">
      <a href="deliverbook.blade.php">
        <img src="Gambar/Icon Bengkulu.jpg" alt="Bengkulu">
      </a>
    </div>
  </div>
  <h3>DELIVER NOW</h3>
</section>

<section class="infographic-section">
<div class="info-box">
  <img src="Gambar/boat.png" class=image>
  <h3>5+ Kapal</h3>
  <p>Terdapat 5+ Kapal yang dimiliki oleh perusahaan PT. Mutiara Nasional Line</p>
  <div class="caption">Caption text here</div>
</div>
<div class="info-box">
  <img src="Gambar/grouping.png" class=image>
  <h3>100 Karyawan</h3>
  <p>Memiliki 100+ karyawan yang sudah bekerja sejak 2013</p>
  <div class="caption">Caption text here</div>
</div>
<div class="info-box">
  <img src="Gambar/stars.png" class=image>
  <h3>5 STAR</h3>
  <p>Mendapatkan respon positif dari customer yang sudah lita pegang kepercayaannya</p>
  <div class="caption">Caption text here</div>
</div>
<div class="info-box no-border">
  <img src="Gambar/customer.png" class=image> <!-- ukurannya 128 px -->
  <h3>99+ Customer</h3>
  <p>Sudah Menjadi kepercayaan kepada 99+ customer sejak 2012</p>
  <div class="caption">Caption text here</div>
</div>
</section>

<section class="pilot-section">
    <h2>ADA KELUHAN TENTANG PERUSAHAAN KAMI?</h2>
    <p>
      ADA KELUHAN ATAU MASUKAN TENTANG WEBSITE DAN PERUSAHAAN KAMI? <br>
      JIKA TIDAK ADA KELUHAN DAN MASUKAN KALIAN BISA LANGSUNG ANTAR DENGAN JASA KAMI.
    </p>
    <div class="button-group">
      <a href="contactus.php"><button class="btn btn-contact">CONTACT US</button></a>
      <button class="btn btn-register" id="scrollBtn">DELIVER NOW</button>

    </div>
  </section>

<footer>
  <div class="footer-top">
    <div class="contact-info">
      <span>(+62) <strong>8XXXXX</strong></span>
      <span>📞</span>
      <span><a href="mailto:ftc@athy.com">mnl.com</a></span>
    </div>
  </div>

  <div class="footer-main">
    <div class="footer-column">
      <a href="aboutus.php"><h4>ABOUT US</h4></a>
    </div>
    <div class="footer-column">
      <a href="contactus.php"><h4>CONTACT US</h4></a>
      <ul>
        <li>PHONE</li>
        <li>EMAIL</li>
        <li>LOCATION</li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>DELIVER</h4>
      <ul>
        <li>JAKARTA</li>
        <li>BENGKULU</li>
        <li>PONTIANAK</li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>FOLLOW US</h4>
      <div class="social-icons">
        <a href="#">🌐</a>
        <a href="#">📘</a>
        <a href="#">🐦</a>
      </div>
    </div>
    <div class="footer-column">
      <h4>PRIVATE POLICY</h4>
      <h4>TERMS & CONDITIONS</h4>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="logos">
      <img src="Gambar/logo1.png" alt="Logo 1">
      <img src="Gambar/logo2.png" alt="PELINDO">
      <img src="Gambar/logo3.png" alt="Seunda Kelapa">
    </div>
    <p>PT. Mutiara Nasional Line</p>
  </div>
</footer>


    <script src="scripts.js"></script>
</body>
</html>