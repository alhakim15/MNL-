<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us</title>
  <link href="{{ asset('css/contact.css') }}" rel="stylesheet">
</head>

<body>

  <!-- Navbar Section -->
  @include('components.navbar')

  <!-- Contact Us Section -->
  <section class="contact-us">
    <h2>Contact Us</h2>

    <div class="contact-content centered">
      <!-- Contact Form -->
      @if(session('success'))
      <div class="alert-success" id="success-message">
        {{ session('success') }}
      </div>
      @endif

      <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Your Email" required />

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" placeholder="Your Phone Number" />

        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Your Name" required />

        <label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" placeholder="Subject" />

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

  @include('components.footer')

  <script>
    setTimeout(() => {
    const msg = document.getElementById('success-message');
    if (msg) {
      msg.style.display = 'none';
    }
  }, 3000); // 3000ms = 3 detik
  </script>


</body>

</html>