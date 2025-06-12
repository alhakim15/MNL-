<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DeliveryController;

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

Route::get('/deliver', [DeliveryController::class, 'create'])->name('deliveries.create');

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::post('/deliver', [DeliveryController::class, 'store'])->name('deliveries.store');
});
