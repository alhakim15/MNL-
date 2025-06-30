<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mutiara Nasional line</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poiret+One&display=swap"
    rel="stylesheet">
</head>

<body>
  <!-- Navbar Section -->
  @include('components.navbar')

  <!-- Email Verification Notice Banner -->
  @auth
  @if(is_null(auth()->user()->email_verified_at))
  <div class="email-verification-banner"
    style="background: linear-gradient(45deg, #ff9800, #f57c00); color: white; padding: 15px 0; text-align: center; position: relative; z-index: 999;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
      <div style="display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="font-size: 24px;">📧</span>
          <span style="font-weight: 600;">Verifikasi email Anda untuk mengakses semua fitur!</span>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
          <a href="{{ route('verification.notice') }}"
            style="background: rgba(255,255,255,0.2); color: white; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-weight: 500; border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease;">
            Verifikasi Sekarang
          </a>
          <form method="POST" action="{{ route('verification.send') }}" style="display: inline; margin: 0;">
            @csrf
            <button type="submit"
              style="background: transparent; color: white; border: 1px solid rgba(255,255,255,0.5); padding: 8px 16px; border-radius: 20px; cursor: pointer; font-weight: 500; transition: all 0.3s ease;">
              Kirim Ulang Email
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif
  @endauth

  <!-- Hero Section with Image -->
  <section class="hero">
    <div class="hero-text">
      <h2>PENGIRIMAN CEPAT<br>ANTAR PONTIANAK<br>DAN BENGKULU</h2>
      <div class="button-group">
        <button type="button" class="search-btn search-btn-left"
          onclick="window.location.href='{{route('aboutus')}}'">LEARN
          MORE</button>
      </div>
    </div>
    <img src="Gambar/kapal cargo.jpg" alt="Modern House" class="hero-image">
  </section>

  <section id="explore" class="explore">
    <div class="carousel-container">
      <button class="carousel-btn prev" aria-label="Previous">&#10094;</button>
      <div class="carousel-wrapper">
        @foreach ($city as $destination)
        <div class="carousel-item">
          <a href="{{ route('deliveries.create') }}" class="carousel-link">
            <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}">
          </a>
          <p>{{$destination->name}}</p>
        </div>
        @endforeach
      </div>
      <button class="carousel-btn next" aria-label="Next">&#10095;</button>
    </div>
    <h3 class="carousel-title">DELIVER NOW</h3>
  </section>

  <section class="infographic-section">
    @forelse($infographics as $info)
    <div class="info-box">
      <img src="{{ asset('storage/' . $info->image) }}" class=image>
      <h3>{{$info->title}}</h3>
      <p>{{$info->description}}</p>
      <div class="caption">{{$info->caption}}</div>
    </div>
    @empty
    <p class="no-infographics">No infographics available.</p>
    @endforelse
  </section>


  <section class="pilot-section">
    <h2>ADA KELUHAN TENTANG PERUSAHAAN KAMI?</h2>
    <p>
      ADA KELUHAN ATAU MASUKAN TENTANG WEBSITE DAN PERUSAHAAN KAMI? <br>
      JIKA TIDAK ADA KELUHAN DAN MASUKAN KALIAN BISA LANGSUNG ANTAR DENGAN JASA KAMI.
    </p>
    <div class="button-group">
      <a href="{{route('contactus')}}"><button class="btn btn-contact">CONTACT US</button></a>
      <button class="btn btn-register" id="scrollBtn"
        onclick="window.location.href='{{route('deliveries.create')}}'">DELIVER NOW</button>

    </div>
  </section>

  @include('components.footer')

  <script src="{{ asset('js/navbar.js') }}"></script>
  <script src="{{ asset('js/pages/home.js') }}"></script>
</body>

</html>