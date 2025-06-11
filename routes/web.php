<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login',[AuthController::class,'index'])->name('login');
Route::get('/register',[AuthController::class,'login'])->name('register');
Route::get('/deliverbook',[AuthController::class,'tampilan'])->name('deliverbook');
Route::get('/aboutus',[AuthController::class,'about'])->name('aboutus');
Route::get('/contactus',[AuthController::class,'contact'])->name('contactus');