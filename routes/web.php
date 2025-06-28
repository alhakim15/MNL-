<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ProfileController;

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
});
