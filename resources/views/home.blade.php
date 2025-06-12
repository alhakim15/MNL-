<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mutiara Nasional line</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poiret+One&display=swap"
    rel="stylesheet">
</head>

<body>
  <!-- Navbar Section -->
  @include('components.navbar')
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
    <div class="carousel-wrapper">
      @foreach ($city as $destination)
      <div class="carousel-item">
        <a href="{{ route('deliveries.create') }}" class="carousel-link">
          <img src="{{ asset('storage/' . $destination->image) }}" alt="{{ $destination->name }}">
        </a>
      </div>
      @endforeach
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


  <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>