# Payment Gateway Integration dengan Midtrans

Aplikasi ini telah diintegrasikan dengan Midtrans sebagai payment gateway untuk menangani pembayaran biaya pengiriman.

## Fitur Payment

1. **Kalkulasi Biaya Otomatis**: Sistem menghitung biaya pengiriman berdasarkan berat dan rute
2. **Payment Gateway Midtrans**: Mendukung berbagai metode pembayaran
3. **Status Tracking**: Melacak status pembayaran secara real-time
4. **Notification Handler**: Menangani callback dari Midtrans

## Setup Midtrans

### 1. Install Package
```bash
composer require midtrans/midtrans-php
```

### 2. Konfigurasi Environment
Tambahkan konfigurasi berikut ke file `.env`:

```env
# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 3. Database Migration
Jalankan migration untuk menambah field payment:
```bash
php artisan migrate
```

## Cara Penggunaan

### 1. Proses Delivery
- User membuat booking pengiriman
- Sistem otomatis menghitung biaya pengiriman
- Modal konfirmasi menampilkan detail termasuk biaya
- User dapat langsung melakukan pembayaran

### 2. Payment Flow
- Klik "Bayar Sekarang" untuk membuka halaman payment
- Sistem membuat payment token melalui Midtrans
- User memilih metode pembayaran
- Midtrans mengirim notification callback
- Status payment diupdate otomatis

### 3. Status Payment
- **Pending**: Menunggu pembayaran
- **Paid**: Pembayaran berhasil
- **Failed**: Pembayaran gagal
- **Expired**: Token pembayaran kedaluwarsa

## File yang Dimodifikasi/Ditambahkan

### Models
- `app/Models/Delivery.php` - Ditambah field payment

### Controllers
- `app/Http/Controllers/DeliveryController.php` - Integrasi dengan MidtransService
- `app/Http/Controllers/PaymentController.php` - Handler payment dan notification

### Services
- `app/Services/MidtransService.php` - Service untuk handle Midtrans API

### Views
- `resources/views/deliverbook.blade.php` - Modal dengan info biaya dan payment button
- `resources/views/payment.blade.php` - Halaman payment
- `resources/views/delivery-history.blade.php` - Tampilkan status payment

### Routes
- `routes/web.php` - Routes untuk payment

### Migration
- `database/migrations/add_payment_fields_to_deliveries_table.php` - Tambah field payment

### Configuration
- `config/services.php` - Konfigurasi Midtrans

## API Endpoints

### Payment Routes
- `GET /payment/{resi}` - Halaman payment
- `POST /payment/notification` - Webhook notification dari Midtrans
- `GET /payment/finish` - Redirect setelah payment sukses
- `GET /payment/unfinish` - Redirect jika payment belum selesai
- `GET /payment/error` - Redirect jika ada error

## Testing

### Sandbox Testing
Untuk testing, gunakan environment sandbox Midtrans:
- Server Key: Dapatkan dari dashboard Midtrans (Sandbox)
- Client Key: Dapatkan dari dashboard Midtrans (Sandbox)
- Set `MIDTRANS_IS_PRODUCTION=false`

### Test Cards
Gunakan test card dari dokumentasi Midtrans untuk testing berbagai skenario pembayaran.

## Security

1. **Server Key Protection**: Server key disimpan di environment variable
2. **Notification Verification**: Verifikasi signature dari Midtrans
3. **Order ID Validation**: Pastikan order ID valid sebelum update status
4. **User Authorization**: Hanya user yang membuat delivery yang bisa akses payment

## Troubleshooting

### Common Issues
1. **Payment token tidak terbuat**: Periksa konfigurasi Midtrans
2. **Notification tidak diterima**: Pastikan URL notification dapat diakses public
3. **Status tidak terupdate**: Check log aplikasi untuk error notification

### Logs
Monitor log aplikasi untuk tracking payment activities:
```bash
tail -f storage/logs/laravel.log
```

## Production Deployment

Untuk production:
1. Ganti ke server key dan client key production
2. Set `MIDTRANS_IS_PRODUCTION=true`
3. Setup proper SSL certificate
4. Configure webhook URL di dashboard Midtrans
