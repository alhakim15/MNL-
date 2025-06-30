<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\EmailVerificationController;

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



// Payment finish route
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');


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

// Email Verification Routes
Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('auth')->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');
Route::post('/tracking', [TrackingController::class, 'search'])->name('tracking.search');


Route::middleware(['auth', 'verified'])->group(function () {
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
    Route::get('/payment/history', [PaymentController::class, 'history'])->name('payment.history');
    Route::get('/payment/{resi}/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
    Route::get('/payment/{resi}/force-update', [PaymentController::class, 'forceUpdateStatus'])->name('payment.force-update');
    Route::get('/payment/{resi}', [PaymentController::class, 'showPayment'])->name('payment.show');
});

// API routes for dynamic ship selection
Route::get('/api/ships-by-route', [DeliveryController::class, 'getShipsByRoute'])->name('api.ships-by-route');
