<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mutiara Nasional Line</title>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;700&family=Poiret+One&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/about.css') }}">
</head>

<body>
  <!-- Navbar -->
  @include('components.navbar')

  <!-- About Us Section -->
  <section class="tentang">
    <div class="tentang-box">
      <img src="{{asset('Gambar/fix.png')}}" alt="Tentang Kami" class="tentang-image">
      <div class="text">
        <h1>About Us</h1>
        <p><strong>Sejarah Singkat PT Mutiara Nasional Line</strong></p>
        <p>PT Mutiara Nasional Line didirikan pada tahun 1995 sebagai agen operasional kapal. Pada tahun 2013,
          perusahaan resmi menjadi perusahaan pelayaran dengan satu kapal. Berkat komitmen dan inovasi, kini PT Mutiara
          Nasional Line mengoperasikan enam kapal milik sendiri dan satu kapal sewaan.</p>
        <p>Armada perusahaan meliputi KM Mutiara Sejahtera, KM Karunia Permai, KM Lintas Bahari 12, KM Lintas Bahari 20,
          KM Bahari 28, KM Kota Nabire, dan KM Vidi (kapal sewaan). Perkembangan ini mencerminkan dedikasi perusahaan
          dalam menyediakan layanan pelayaran profesional di industri maritim.</p>
        <p>Logo perusahaan bergambar bendera merah bertuliskan "MNL" putih melambangkan keberanian, integritas, dan
          profesionalisme — nilai-nilai utama yang menjadi fondasi operasional perusahaan.</p>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <div class="feature-box">
      <img src="{{asset('Gambar/cruise.png')}}" alt="Pengalaman dan Armada">
      <div class="feature-text">
        <h3>Pengalaman dan Armada</h3>
        <p>Armada kapal modern dan handal didukung oleh pengalaman panjang dalam industri pelayaran.</p>
      </div>
    </div>
    <div class="feature-box">
      <img src="{{asset('Gambar/shield.png')}}" alt="Keamanan dan Ketepatan">
      <div class="feature-text">
        <h3>Keamanan dan Ketepatan</h3>
        <p>Kami berkomitmen terhadap keamanan dan ketepatan pengiriman barang Anda.</p>
      </div>
    </div>
    <div class="feature-box">
      <img src="{{asset('Gambar/order-trac')}}king.png" alt="Tracking Canggih">
      <div class="feature-text">
        <h3>Tracking Canggih</h3>
        <p>Teknologi pelacakan barang real-time yang canggih dan mudah digunakan oleh pelanggan.</p>
      </div>
    </div>
    <div class="feature-box">
      <img src="{{asset('Gambar/people.png')}}" alt="Tim Profesional">
      <div class="feature-text">
        <h3>Tim Profesional</h3>
        <p>Tim profesional bersertifikasi yang menjunjung tinggi kepercayaan dan integritas.</p>
      </div>
    </div>
    <div class="feature-box">
      <img src="{{asset('Gambar/responsible')}}.png" alt="Pelayanan Pelanggan">
      <div class="feature-text">
        <h3>Pelayanan Pelanggan</h3>
        <p>Pelayanan pelanggan yang responsif dan terpercaya untuk membantu kebutuhan Anda.</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
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
        <a href="aboutus.php">
          <h4>ABOUT US</h4>
        </a>
      </div>
      <div class="footer-column">
        <a href="contactus.php">
          <h4>CONTACT US</h4>
        </a>
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
        <img src="{{asset('Gambar/logo1.png')}}" alt="Logo 1">
        <img src="{{asset(' Gambar/logo2.png')}}" alt="PELINDO">
        <img src="{{asset('Gambar/logo3.png')}}" alt="Seunda Kelapa">
      </div>
      <p>PT. Mutiara Nasional Line</p>
    </div>1
  </footer>
</body>

</html>