<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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

Route::get('/deliverbook',[HomeController::class,'tampilan'])->name('deliverbook');
Route::get('/aboutus',[HomeController::class,'about'])->name('aboutus');
Route::get('/contactus',[HomeController::class,'contact'])->name('contactus');
Route::get('/',[HomeController::class,'index'])->name('home');