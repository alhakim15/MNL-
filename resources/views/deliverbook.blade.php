<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Delivery Booking</title>
  <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
  <nav class="back-nav">
    <a href="{{ route('home') }}" class="back-button">
      <i class="fas fa-arrow-left"></i>
      <span>Back</span>
    </a>
    <a href="{{ route('payment.dashboard') }}" class="back-button history-button">
      <i class="fas fa-credit-card"></i>
      <span>Payments</span>
    </a>
    <a href="{{ route('deliveries.history') }}" class="back-button history-button">
      <i class="fas fa-history"></i>
      <span>History</span>
    </a>
  </nav>

  <div class="main-container">
    <section class="welcome-section">
      <div class="welcome">
        <h2>Welcome 👋</h2>
        <h3>Liners</h3>
      </div>
      <div class="subtitle">Pengiriman Aman, Kirim Tepat Waktu</div>
    </section>

    <section class="booking-container">
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

      <form method="POST" action="{{ route('deliveries.store') }}">
        @csrf
        <h3 class="form-title">Delivery Information</h3>

        <div class="form-row">
          <div class="input-group">
            <label for="sender_name">Nama Pengirim</label>
            <div class="input-with-icon">
              <i class="fas fa-user"></i>
              <input type="text" id="sender_name" name="sender_name" value="{{ Auth::user()->name }}" readonly>
            </div>
          </div>

          <div class="input-group">
            <label for="receiver_name">Nama Penerima</label>
            <div class="input-with-icon">
              <i class="fas fa-user-tag"></i>
              <input type="text" id="receiver_name" name="receiver_name" placeholder="Nama penerima" required>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="input-group">
            <label for="from_city_id">From</label>
            <div class="input-with-icon">
              <i class="fas fa-map-marker-alt"></i>
              <select id="from_city_id" name="from_city_id" required>
                <option value="">Select departure city</option>
                @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ old('from_city_id')==$city->id ? 'selected' : '' }}>{{ $city->name }}
                </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="input-group">
            <label for="to_city_id">To</label>
            <div class="input-with-icon">
              <i class="fas fa-map-marker-alt"></i>
              <select id="to_city_id" name="to_city_id" required>
                <option value="">Select arrival city</option>
                @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ old('to_city_id')==$city->id ? 'selected' : '' }}>{{ $city->name }}
                </option>
                @endforeach
              </select>
            </div>
            <div id="route-info" class="route-info"></div>
          </div>
        </div>

        <div class="form-row">
          <div class="input-group">
            <label for="delivery_date">Date</label>
            <div class="input-with-icon">
              <i class="fas fa-calendar-alt"></i>
              <input type="date" id="delivery_date" name="delivery_date" required>
            </div>
          </div>

          <div class="input-group">
            <label for="item_name">Nama Barang</label>
            <div class="input-with-icon">
              <i class="fas fa-box"></i>
              <input type="text" id="item_name" name="item_name" placeholder="Nama barang" required>
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="input-group">
            <label for="weight">Weight (ton)</label>
            <div class="input-with-icon">
              <i class="fas fa-weight-hanging"></i>
              <input type="number" id="weight" name="weight" placeholder="Payload Weight" required step="0.1">
            </div>
          </div>

          <div class="input-group">
            <label for="ship_id">Pilih Kapal</label>
            <div class="input-with-icon">
              <i class="fas fa-ship"></i>
              <select id="ship_id" name="ship_id" required disabled>
                <option value="">Pilih rute terlebih dahulu</option>
              </select>
            </div>
            <div id="capacity-info" class="capacity-notice"></div>
            <div id="ship-loading" class="loading-notice" style="display: none;">
              <i class="fas fa-spinner fa-spin"></i> Memuat kapal yang tersedia...
            </div>
          </div>
        </div>

        <button type="submit" class="submit-button">
          <i class="fas fa-paper-plane"></i> Deliver Now
        </button>
      </form>
    </section>
  </div>

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