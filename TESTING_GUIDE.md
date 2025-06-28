# Testing Payment Integration

## Setup dan Test Aplikasi

### 1. Pastikan Konfigurasi Midtrans di .env
```bash
MIDTRANS_SERVER_KEY=SB-Mid-server-4NVBQziJNsuztTsrbZ7SQ2J7
MIDTRANS_CLIENT_KEY=SB-Mid-client-AzruIyoZre7ipUmj
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 2. Jalankan Server
```bash
php artisan serve
```

### 3. Testing Flow

#### A. Buat Delivery Baru
1. Login ke aplikasi
2. Pergi ke "Deliver Book" dari navbar
3. Isi form pengiriman:
   - Nama Penerima: Test User
   - From: Pilih kota asal
   - To: Pilih kota tujuan  
   - Date: Pilih tanggal pengiriman
   - Nama Barang: Test Package
   - Weight: 1 (ton)
   - Pilih Kapal: Pilih salah satu kapal
4. Klik "Deliver Now"

#### B. Konfirmasi dan Payment
1. Modal akan muncul menampilkan:
   - Detail pengiriman
   - Resi number
   - Biaya pengiriman (otomatis dihitung)
   - Button "Bayar Sekarang"
   - Button "Payment Dashboard"
2. Klik "Bayar Sekarang" untuk langsung ke halaman payment
3. Atau klik "Payment Dashboard" untuk melihat dashboard

#### C. Payment Dashboard
1. Akses via navbar "Payments" atau `/payment/dashboard`
2. Dashboard menampilkan:
   - Total Pengiriman
   - Pembayaran Pending 
   - Pembayaran Lunas
   - Total Amount yang sudah dibayar
   - List Pembayaran Pending
   - List Pembayaran Terbaru
3. Klik "Bayar" pada pembayaran pending

#### D. Payment Process
1. Halaman payment menampilkan:
   - Detail lengkap pengiriman
   - Total biaya
   - Button "Bayar Sekarang" (Midtrans)
2. Klik "Bayar Sekarang"
3. Popup Midtrans akan muncul
4. Gunakan test card numbers:
   - Success: `4811 1111 1111 1114`
   - Failure: `4911 1111 1111 1113`
   - Challenge: `4411 1111 1111 1118`

#### E. Payment History
1. Akses via `/payment/history`
2. Filter berdasarkan status:
   - All: Semua pembayaran
   - Pending: Yang belum dibayar
   - Paid: Yang sudah dibayar
   - Failed: Yang gagal

### 4. Pages yang Tersedia

#### User Pages:
- `/payment/dashboard` - Payment Dashboard
- `/payment/history` - Payment History  
- `/payment/{resi}` - Payment Detail
- `/deliveries/create` - Buat Pengiriman Baru
- `/deliveries/history` - History Pengiriman

#### Webhook (Midtrans Callback):
- `/payment/notification` - Webhook notification
- `/payment/finish` - Redirect success
- `/payment/unfinish` - Redirect unfinish
- `/payment/error` - Redirect error

### 5. Features

#### Payment Dashboard:
- ✅ Statistics cards (Total, Pending, Paid, Amount)
- ✅ Pending payments list dengan action buttons
- ✅ Recent payments history
- ✅ Responsive design dengan glassmorphism effect

#### Payment History:
- ✅ Filter by status (All, Pending, Paid, Failed)
- ✅ Detailed payment cards
- ✅ Action buttons (Pay, Track)
- ✅ Pagination support
- ✅ Empty state handling

#### Payment Process:
- ✅ Midtrans Snap integration
- ✅ Automatic cost calculation
- ✅ Real-time status updates via webhook
- ✅ Multiple payment methods support

#### Integration:
- ✅ Auto redirect setelah delivery dibuat
- ✅ Navigation links di navbar
- ✅ Modal dengan payment info
- ✅ Consistent design dengan tema aplikasi

### 6. Test Cards (Midtrans Sandbox)

```
Success Payment:
Card Number: 4811 1111 1111 1114
CVV: 123
Exp Date: 01/25

Failed Payment:
Card Number: 4911 1111 1111 1113  
CVV: 123
Exp Date: 01/25

Challenge/3DS:
Card Number: 4411 1111 1111 1118
CVV: 123
Exp Date: 01/25
```

### 7. Database Changes

Fields ditambahkan ke table `deliveries`:
- `shipping_cost` - Biaya pengiriman (auto calculated)
- `payment_status` - Status pembayaran (pending/paid/failed/expired)
- `payment_token` - Token dari Midtrans
- `payment_type` - Metode pembayaran yang digunakan
- `paid_at` - Timestamp kapan dibayar

### 8. Troubleshooting

#### Error: Payment token tidak terbuat
- Periksa konfigurasi MIDTRANS_SERVER_KEY
- Pastikan koneksi internet stabil

#### Error: Status tidak terupdate
- Check log: `tail -f storage/logs/laravel.log`
- Pastikan webhook URL dapat diakses (untuk production)

#### Error: Halaman tidak ditemukan
- Jalankan: `php artisan route:clear`
- Pastikan semua routes terdaftar: `php artisan route:list | grep payment`

### 9. Production Deployment

Untuk production:
1. Ganti ke production credentials di Midtrans dashboard
2. Update .env:
   ```
   MIDTRANS_IS_PRODUCTION=true
   ```
3. Setup SSL certificate
4. Configure webhook URL di Midtrans dashboard ke: `https://yourdomain.com/payment/notification`
