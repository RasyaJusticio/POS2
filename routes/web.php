<?php

use App\Http\Controllers\ItemController;
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

// Halaman awal (bisa dikosongkan atau diarahkan ke dashboard)
Route::get('/', function () {
    return view('app');
});

// Resource untuk Item
Route::resource('items', ItemController::class);

// Prefix untuk rute master
Route::prefix('master')->group(function () {
    Route::resource('items', ItemController::class);
});

// Rute khusus POS System
Route::prefix('dashboard/pos/pos-sistem')->group(function () {
    Route::resource('items', ItemController::class);
});

// Menangani rute wildcard
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api\/)[\/\w\.-]*');
