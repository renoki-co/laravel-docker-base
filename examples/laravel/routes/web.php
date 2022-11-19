<?php

use App\Http\Controllers\LogRequestController;
use App\Http\Controllers\RandomImageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/', LogRequestController::class)->name('log-request');
Route::any('/image', RandomImageController::class)->name('random-image');
Route::get('/health', fn () => 'OK')->name('healthcheck');
