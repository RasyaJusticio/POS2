<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;

use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- 
| API Routes 
|-------------------------------------------------------------------------- 
*/

// Authentication Route
Route::middleware(['json'])->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('auth');
    Route::post('reset-password', [AuthController::class, 'reset']);
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::prefix('setting')->group(function () {
    Route::get('', [SettingController::class, 'index']);
});

// API routes for reservations
// Route untuk membuat reservasi
Route::post('/reservations', [ReservationController::class, 'store']);

// Route untuk mendapatkan semua reservasi
Route::get('/reservations', [ReservationController::class, 'index']);
Route::get('/dashboard/stats', [ReservationController::class, 'getDashboardStats']);
Route::get('/reservations/count', [ReservationController::class, 'countReservations']);
Route::get('/total-customers', [ReservationController::class, 'totalCustomers']);
Route::get('/reservations/summary', [ReservationController::class, 'totalSummary']);
Route::get('/api/reservations/customers-per-month', [ReservationController::class, 'getCustomersPerMonth']);



Route::middleware(['auth', 'verified', 'json'])->group(function () {
    Route::prefix('setting')->middleware('can:setting')->group(function () {
        Route::post('', [SettingController::class, 'update']);
    });

    Route::prefix('master')->group(function () {
        Route::middleware('can:master-user')->group(function () {
            Route::get('users', [UserController::class, 'get']);
            Route::post('users', [UserController::class, 'index']);
            Route::post('users/store', [UserController::class, 'store']);
            Route::apiResource('users', UserController::class)
                ->except(['index', 'store'])->scoped(['user' => 'uuid']);
        });

        Route::middleware('can:master-role')->group(function () {
            Route::get('roles', [RoleController::class, 'get'])->withoutMiddleware('can:master-role');
            Route::post('roles', [RoleController::class, 'index']);
            Route::post('roles/store', [RoleController::class, 'store']);
            Route::apiResource('roles', RoleController::class)
                ->except(['index', 'store']);
        });

        // Rute untuk mendapatkan daftar item
        Route::post('pos-items', [ItemController::class, 'index']);

        // Rute untuk membuat item baru
        Route::post('pos-items/store', [ItemController::class, 'store']);

        // Rute untuk mendapatkan item berdasarkan ID
        Route::get('pos-items/{id}', [ItemController::class, 'show']);

        // Rute untuk memperbarui item
        Route::put('pos-items/{id}', [ItemController::class, 'update']);

        // Rute untuk menghapus item
        Route::delete('pos-items/{id}', [ItemController::class, 'destroy']);
    });
});

// Rute untuk Orders
Route::prefix('orders')->group(function () {
    Route::post('/checkout/{uuid}', [OrderController::class, 'payment']);
    Route::get('/show/{uuid}', [OrderController::class, 'show']);
});

// Rute untuk Produk
Route::prefix('inventori')->group(function () {
    Route::middleware('can:inventori-produk')->group(function () {
        Route::group(['prefix' => 'produk'], function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/', [ProductController::class, 'index']);
            Route::post('/store', [ProductController::class, 'store']);
            
            Route::group(['prefix' => '{id}'], function () {
                Route::get('/', [ProductController::class, 'show']); // GET: produk/{product_id}
                Route::post('/', [ProductController::class, 'update']);
                Route::delete('/', [ProductController::class, 'destroy']);
                Route::post('/toggle-sold-out', [ProductController::class, 'toggleSoldOut']);

            });
        });


        // Route::prefix('inventori')->group(function () {
        //     Route::middleware('can:inventori-produk')->group(function () {
        //         Route::group(['prefix' => 'produk'], function () {
        //             // Route::get('/', [ProductController::class, 'get']);
        //             Route::get('/', [ProductController::class, 'index'])->withoutMiddleware('can:inventori-produk');
        //             Route::post('/', [ProductController::class, 'index']);
        //             Route::post('/store', [ProductController::class, 'store']);
                    
        //             Route::group(['prefix' => '{id}'], function () { // produk/{product_id}
        //                 Route::get('/', [ProductController::class, 'show']); // GET: produk/{product_id}
        //                 Route::post('/', [ProductController::class, 'update']);
        //                 Route::delete('/', [ProductController::class, 'destroy']);
        //                 Route::post('/toggle-sold-out', [ProductController::class, 'toggleSoldOut']);

        //             });
        //         });
        //         // Route::apiResource('produk', ProductController::class)
        //         //     ->except(['index', 'store'])->scoped(['product' => 'id']);
        //     });
    
        // });
});
});
