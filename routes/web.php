<?php

use Illuminate\Support\Facades\Route;

// === Auth Controllers ===
use App\Http\Controllers\Auth\LoginController;

// === Panel Controllers ===
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TableController;

use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\MenuController as KasirMenuController;
use App\Http\Controllers\Kasir\OrderController as KasirOrderController;

// === Public (Pelanggan) ===
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;

/*
|--------------------------------------------------------------------------
| Public Routes — Pelanggan (tanpa login)
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Scan QR → masuk halaman pesan
Route::get('/order/{qr_token}', [CustomerOrderController::class, 'index'])->name('customer.menu');
Route::post('/order/{qr_token}', [CustomerOrderController::class, 'store'])->name('customer.order.store');
Route::get('/order/{qr_token}/status/{order}', [CustomerOrderController::class, 'status'])->name('customer.order.status');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/profile', function () {
    return view('profile');
})->name('profile')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Kelola Menu
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('menus', AdminMenuController::class);

        // Kelola Meja
        Route::resource('tables', TableController::class);

        // Transaksi
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Laporan
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

        // Kelola Pengguna
        Route::resource('users', UserController::class);
    });

/*
|--------------------------------------------------------------------------
| Kasir Routes
|--------------------------------------------------------------------------
*/

Route::prefix('kasir')
    ->middleware(['auth', 'role:kasir'])
    ->name('kasir.')
    ->group(function () {
        Route::get('/', [KasirDashboardController::class, 'index'])->name('dashboard');

        // Lihat Menu (read-only)
        Route::get('menus', [KasirMenuController::class, 'index'])->name('menus.index');
        Route::get('menus/{menu}', [KasirMenuController::class, 'show'])->name('menus.show');

        // Transaksi
        Route::get('orders', [KasirOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [KasirOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [KasirOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('orders/{order}/pay', [KasirOrderController::class, 'pay'])->name('orders.pay');
    });
