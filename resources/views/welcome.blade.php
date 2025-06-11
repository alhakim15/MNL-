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
  @include('components.navbar')

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
      <a href="{{ route('deliverbook') }}">
        <img src="Gambar/Tugu Khatulistiwa.jpg" alt="Pontianak">
      </a>
    </div>
    <div class="carousel-item">
      <a href="{{ route('deliverbook') }}">
        <img src="Gambar/Icon Bengkulu.jpg" alt="Bengkulu">
      </a>
    </div>
    <div class="carousel-item">
      <a href="{{ route('deliverbook') }}">
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

  @include('components.footer')

    <script src="scripts.js"></script>
</body>
</html>