<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Flight Booking</title>
<link href="{{ asset('css/deliverbook.css') }}" rel="stylesheet" > 
</head>
<body>
  <!-- Bagian Selamat Datang di pojok kiri -->
  <section class="welcome-section">
    <div class="welcome">
      <h2>Welcome 👋</h2>
      <h3>Liners</h3>
    </div>
    <div class="subtitle">
      Pengiriman Aman Kirim Tepat Waktu
    </div>
  </section> 

  <!-- Form Booking di posisi terpisah -->
  <section class="booking-container">
    <form method="POST">
      <div class="input-group">
        <span>Nama Pengirim</span>
        <input type="text" name="Name" placeholder="Nama pengirim" required step="0.1">
      </div>
      <div class="input-group">
        <span>Nama Penerima</span>
        <input type="text" name="Name" placeholder="Nama penerima" required step="0.1">
      </div>
      <div class="input-group">
      <span>From</span>
        <select name="from" required>
        <option value="">Select departure city</option>
        <option value="Jakarta">Jakarta</option>
        <option value="Pontianak">Pontianak</option>
        <option value="Bengkulu">Bengkulu</option>
      </select>
      </div>

      <div class="input-group">
  <span>To</span>
  <select name="to" required>
    <option value="">Select arrival city</option>
    <option value="Jakarta">Jakarta</option>
    <option value="Pontianak">Pontianak</option>
    <option value="Bengkulu">Bengkulu</option>
  </select>
</div>

      <div class="input-group">
        <span>Date</span>
        <input type="date" name="date" required>
      </div>

<div class="input-group">
        <span>Nama Barang</span>
        <input type="text" name="Name" placeholder="Nama barang" required step="0.1">
      </div>

      <div class="input-group">
        <span>Weight</span>
        <input type="number" name="weight" placeholder="Payload Weight (ton)" required step="0.1">
      </div>

      <button type="submit">Deliver</button>
    </form>
  </section>
</body>
</html>