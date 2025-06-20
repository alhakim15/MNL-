<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Delivery Booking</title>
  <link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .capacity-notice {
      margin-top: 0.5rem;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <!-- Navigation Back Button -->
  <nav class="back-nav">
    <a href="{{ route('home')}}" class="back-button">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </nav>

  <!-- Main Content Container -->
  <div class="main-container">
    <!-- Welcome Section -->
    <section class="welcome-section">
      <div class="welcome">
        <h2>Welcome 👋</h2>
        <h3>Liners</h3>
      </div>
      <div class="subtitle">
        Pengiriman Aman, Kirim Tepat Waktu
      </div>
    </section>

    <!-- Booking Form Section -->
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
              <input type="text" id="sender_name" name="sender_name" placeholder="Nama pengirim">
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
                <option value="{{ $city->id }}">{{ $city->name }}</option>
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
                <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
              </select>
            </div>
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
              <select id="ship_id" name="ship_id" required>
                <option value="">Pilih Kapal</option>
                @foreach ($ships as $ship)
                <option value="{{ $ship->id }}" data-remaining="{{ $ship->remaining_weight }}"
                  data-max="{{ $ship->max_weight }}">
                  {{ $ship->name }} (Max: {{ $ship->max_weight }} ton)
                </option>
                @endforeach
              </select>
            </div>
            <div id="capacity-info" class="capacity-notice"></div>
          </div>
        </div>

        <button type="submit" class="submit-button">
          <i class="fas fa-paper-plane"></i> Deliver Now
        </button>
      </form>
    </section>
  </div>

  <script>
    const shipSelect = document.getElementById('ship_id');
    const capacityInfo = document.getElementById('capacity-info');

    shipSelect.addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const remaining = selectedOption.getAttribute('data-remaining');
      const max = selectedOption.getAttribute('data-max');

      if (remaining !== null) {
        capacityInfo.textContent = `Sisa kapasitas kapal: ${remaining} ton dari total ${max} ton`;
        capacityInfo.style.color = (remaining < 5) ? 'red' : 'green';
      } else {
        capacityInfo.textContent = '';
      }
    });

      setTimeout(() => {
    const errorMsg = document.querySelector('.error-message');
    if (errorMsg) {
      errorMsg.style.display = 'none';
    }

    const successMsg = document.querySelector('.success-message');
    if (successMsg) {
      successMsg.style.display = 'none';
    }
  }, 3000);

   const fromCitySelect = document.getElementById('from_city_id');
  const toCitySelect = document.getElementById('to_city_id');

  fromCitySelect.addEventListener('change', function () {
    const selectedFrom = this.value;

    // Tampilkan semua opsi terlebih dahulu
    Array.from(toCitySelect.options).forEach(opt => {
      opt.hidden = false;
    });

    // Sembunyikan opsi tujuan yang sama dengan asal
    Array.from(toCitySelect.options).forEach(opt => {
      if (opt.value === selectedFrom && selectedFrom !== "") {
        opt.hidden = true;

        // Jika sudah terpilih tujuan yang sama, reset
        if (toCitySelect.value === selectedFrom) {
          toCitySelect.value = "";
        }
      }
    });
  });
  </script>
</body>

</html>