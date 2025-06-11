<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us</title>
<link href="{{ asset('css/contact.css') }}" rel="stylesheet" > 
</head>

<body>

  <!-- Navbar Section -->
  <header class="navbar">
    <div class="navbar-container">
      <h1 class="logo">Mutiara Nasional Line</h1>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="aboutus.php">About Us</a></li>
          <li><a href="contactus.php">Contact Us</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Contact Us Section -->
  <section class="contact-us">
    <h2>Contact Us</h2>

    <div class="contact-content centered">
      <!-- Contact Form -->
      <form action="submit_contact.php" method="POST" class="contact-form">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required />

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" placeholder="Your Phone Number" />

        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required />

        <label for="message">Message</label>
        <textarea id="message" name="message" placeholder="Your Message" required></textarea>

        <button type="submit" class="submit-btn">Kirim</button>
      </form>
    </div>

    <!-- Contact Info -->
    <div class="contact-info">
      <div class="info-item">
        <h4>Phone</h4>
        <p>(+876) XXXXX</p>
      </div>
      <div class="info-item">
        <h4>Email</h4>
        <p>Example@gmail.com</p>
      </div>
      <div class="info-item">
        <h4>Location</h4>
        <p>Jakarta Utara, Sunda Kelapa</p>
      </div>
    </div>

    <!-- Map -->
    <div class="map">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1493556871597!2d106.81996267584176!3d-6.241887093745097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3d776d3cb71%3A0xb4f03d4aeb3d6c57!2sPelabuhan%20Sunda%20Kelapa!5e0!3m2!1sid!2sid!4v1716890000000!5m2!1sid!2sid"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </section>

  <!-- Footer -->
  
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

</body>

</html>