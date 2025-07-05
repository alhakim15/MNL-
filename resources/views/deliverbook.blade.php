<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>StayHub - Delivery Booking</title>
  <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <!-- Include Navbar Component -->

  <!-- Back Button -->
  <div class="back-button-container">
    <a href="{{ route('home') }}" class="back-btn">
      <i class="fas fa-arrow-left"></i>
      <span>Kembali</span>
    </a>
  </div>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <div class="hero-text">
        <p class="hero-subtitle">Pengiriman Aman. Tepat Waktu.</p>
        <h1 class="hero-title">Kirim Paket Dengan Mudah</h1>
        <p class="hero-description">Layanan pengiriman terpercaya dengan armada kapal modern untuk kebutuhan logistik
          Anda di seluruh Indonesia.</p>
      </div>

      <!-- Delivery Booking Form -->
      <div class="search-form-container">
        @if ($errors->any())
        <div class="error-message">
          <ul>
            @foreach ($errors->all() as $error)
            <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        @if(session('success'))
        <div class="success-message">
          <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('deliveries.store') }}" class="booking-form">
          @csrf

          <div class="form-grid">
            <!-- Sender Name -->
            <div class="form-field">
              <label for="sender_name">Nama Pengirim</label>
              <div class="input-container">
                <input type="text" id="sender_name" name="sender_name" value="{{ Auth::user()->name }}" readonly>
                <i class="fas fa-user input-icon"></i>
              </div>
            </div>

            <!-- Receiver Name -->
            <div class="form-field">
              <label for="receiver_name">Nama Penerima</label>
              <div class="input-container">
                <input type="text" id="receiver_name" name="receiver_name" placeholder="Nama penerima" required>
                <i class="fas fa-user-tag input-icon"></i>
              </div>
            </div>

            <!-- From City -->
            <div class="form-field">
              <label for="from_city_id">Kota Asal</label>
              <div class="select-container">
                <select id="from_city_id" name="from_city_id" required>
                  <option value="">Pilih kota asal</option>
                  @foreach ($cities as $city)
                  <option value="{{ $city->id }}" {{ old('from_city_id')==$city->id ? 'selected' : '' }}>{{ $city->name
                    }}</option>
                  @endforeach
                </select>
                <i class="fas fa-chevron-down select-arrow"></i>
                <i class="fas fa-map-marker-alt input-icon"></i>
              </div>
            </div>

            <!-- To City -->
            <div class="form-field">
              <label for="to_city_id">Kota Tujuan</label>
              <div class="select-container">
                <select id="to_city_id" name="to_city_id" required>
                  <option value="">Pilih kota tujuan</option>
                  @foreach ($cities as $city)
                  <option value="{{ $city->id }}" {{ old('to_city_id')==$city->id ? 'selected' : '' }}>{{ $city->name }}
                  </option>
                  @endforeach
                </select>
                <i class="fas fa-chevron-down select-arrow"></i>
                <i class="fas fa-map-marker-alt input-icon"></i>
              </div>
              <div id="route-info" class="route-info"></div>
            </div>

            <!-- Delivery Date -->
            <div class="form-field">
              <label for="delivery_date">Tanggal Pengiriman</label>
              <div class="input-container">
                <input type="date" id="delivery_date" name="delivery_date" required>
                <i class="fas fa-calendar-alt input-icon"></i>
              </div>
            </div>

            <!-- Item Name -->
            <div class="form-field">
              <label for="item_name">Nama Barang</label>
              <div class="input-container">
                <input type="text" id="item_name" name="item_name" placeholder="Nama barang yang dikirim" required>
                <i class="fas fa-box input-icon"></i>
              </div>
            </div>

            <!-- Weight -->
            <div class="form-field">
              <label for="weight">Berat (ton)</label>
              <div class="input-container">
                <input type="number" id="weight" name="weight" placeholder="Berat barang" required step="0.1">
                <i class="fas fa-weight-hanging input-icon"></i>
              </div>
            </div>

            <!-- Ship Selection -->
            <div class="form-field">
              <label for="ship_id">Pilih Kapal</label>
              <div class="select-container">
                <select id="ship_id" name="ship_id" required disabled>
                  <option value="">Pilih rute terlebih dahulu</option>
                </select>
                <i class="fas fa-chevron-down select-arrow"></i>
                <i class="fas fa-ship input-icon"></i>
              </div>
              <div id="capacity-info" class="capacity-notice"></div>
              <div id="ship-loading" class="loading-notice" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Memuat kapal yang tersedia...
              </div>
            </div>
          </div>

          <button type="submit" class="discover-btn">
            <i class="fas fa-paper-plane"></i>
            Kirim Sekarang
          </button>
        </form>
      </div>
    </div>

  </section>

  {{-- MODAL --}}
  @if(session('deliveryData'))
  <div id="deliveryModal" class="modal-overlay">
    <div class="modal-box">
      <h3>Pengiriman Berhasil!</h3>
      <p><strong>Resi:</strong> <span id="resiNumber">{{ session('deliveryData.resi') }}</span></p>
      <p><strong>Penerima:</strong> <span id="receiverName">{{ session('deliveryData.receiver_name') }}</span></p>
      <p><strong>Dari:</strong> <span id="fromCity">{{ session('deliveryData.from') }}</span>
        → <strong>Ke:</strong> <span id="toCity">{{ session('deliveryData.to') }}</span>
      </p>
      <p><strong>Barang:</strong> <span id="itemName">{{ session('deliveryData.item') }}</span>
        ( <span id="weightTon">{{ session('deliveryData.weight') }}</span> ton )
      </p>
      <p><strong>Kapal:</strong> <span id="shipName">{{ session('deliveryData.ship') }}</span></p>
      <p><strong>Tanggal:</strong> <span id="deliveryDate">{{ session('deliveryData.date') }}</span></p>
      <p><strong>Biaya Pengiriman:</strong> <span id="shippingCost" style="color: #e74c3c; font-weight: bold;">Rp {{
          session('deliveryData.shipping_cost') }}</span></p>
      <div class="modal-button-group">
        <button onclick="closeModal()" class="modal-btn modal-btn-close">
          <i class="fas fa-times"></i> Tutup
        </button>
        <a href="{{ session('deliveryData.payment_url') }}" class="modal-btn modal-btn-payment"
          style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
          <i class="fas fa-credit-card"></i> Bayar Sekarang
        </a>
        <a href="{{ route('payment.dashboard') }}" class="modal-btn modal-btn-history">
          <i class="fas fa-tachometer-alt"></i> Payment Dashboard
        </a>
        <a href="{{ route('deliveries.history') }}" class="modal-btn modal-btn-history">
          <i class="fas fa-history"></i> Lihat History
        </a>
      </div>
    </div>
  </div>
  @endif

  <script src="{{ asset('js/pages/deliver.js') }}"></script>
</body>

</html>