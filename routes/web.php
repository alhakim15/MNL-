<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Payment callback routes (MUST BE FIRST - no auth needed)
Route::get('/payment/test', function () {
    return response()->json([
        'status' => 'working',
        'timestamp' => now(),
        'message' => 'Payment routes are accessible'
    ]);
})->name('payment.test');

// Simple finish route for debugging
Route::get('/payment/finish', function (\Illuminate\Http\Request $request) {
    // Log semua data yang masuk
    \Illuminate\Support\Facades\Log::info('=== PAYMENT FINISH ACCESSED ===', [
        'all_request' => $request->all(),
        'query_string' => $request->getQueryString(),
        'full_url' => $request->fullUrl(),
        'method' => $request->method(),
        'timestamp' => now()
    ]);

    $orderId = $request->get('order_id');
    \Illuminate\Support\Facades\Log::info('Order ID received: ' . $orderId);

    if ($orderId) {
        try {
            // Cari delivery
            $delivery = \App\Models\Delivery::where('resi', $orderId)->first();
            \Illuminate\Support\Facades\Log::info('Delivery search result for ' . $orderId . ': ' . ($delivery ? 'FOUND' : 'NOT FOUND'));

            if ($delivery) {
                \Illuminate\Support\Facades\Log::info('Delivery details', [
                    'resi' => $delivery->resi,
                    'current_status' => $delivery->payment_status,
                    'sender_name' => $delivery->sender_name
                ]);

                // Update status ke paid
                $delivery->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);

                \Illuminate\Support\Facades\Log::info('Payment status updated to paid for: ' . $orderId);

                // Return success page dengan delivery data
                return view('payment.success', ['delivery' => $delivery]);
            } else {
                \Illuminate\Support\Facades\Log::error('Delivery not found for order_id: ' . $orderId);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in finish route: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Exception trace: ' . $e->getTraceAsString());
        }
    } else {
        \Illuminate\Support\Facades\Log::error('No order_id provided in finish route');
    }

    // Jika gagal, return not-found page
    return view('payment.not-found');
})->name('payment.finish');

Route::get('/payment/debug-finish', [PaymentController::class, 'debugFinish'])->name('payment.debug-finish');
Route::get('/payment/not-found', [PaymentController::class, 'notFound'])->name('payment.not-found');
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');

//tampilan awal
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/aboutus', [HomeController::class, 'about'])->name('aboutus');

Route::get('/contactus', [ContactController::class, 'index'])->name('contactus');
Route::post('/contactus', [ContactController::class, 'store'])->name('contact.store');

//autentikasi

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::post('/tracking', [TrackingController::class, 'search'])->name('tracking.search');


Route::middleware('auth')->group(function () {
    Route::get('/deliver', [DeliveryController::class, 'create'])->name('deliveries.create');
    Route::post('/deliver', [DeliveryController::class, 'store'])->name('deliveries.store');
    Route::get('/delivery-history', [DeliveryController::class, 'history'])->name('deliveries.history');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'deleteAccount'])->name('profile.delete');

    // Payment routes
    Route::get('/payment/dashboard', [PaymentController::class, 'dashboard'])->name('payment.dashboard');
    Route::get('/payment/debug', [PaymentController::class, 'dashboardDebug'])->name('payment.debug');
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');
    Route::get('/payment/{resi}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
    Route::get('/payment/{resi}/force-update', [PaymentController::class, 'forceUpdateStatus'])->name('payment.force-update');
    Route::get('/payment/{resi}', [PaymentController::class, 'showPayment'])->name('payment.show');
});

// Simple test route to debug payment finish
Route::get('/payment/test-finish', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('Test finish route accessed', $request->all());

    return response()->json([
        'status' => 'test_finish_working',
        'order_id' => $request->get('order_id'),
        'transaction_status' => $request->get('transaction_status'),
        'status_code' => $request->get('status_code'),
        'timestamp' => now(),
        'all_params' => $request->all()
    ]);
})->name('payment.test-finish');
