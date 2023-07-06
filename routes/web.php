<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MarketplaceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

/*
| Web Routes:
| Here is where you can register web routes for your application. 
| These routes are loaded by the RouteServiceProvider 
| and all of them will be assigned to the "web" middleware group. Make something great!
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [GeneralController::class, 'index'])->name('index');

// Seller
Route::get('/seller/registration', [SellerController::class, 'create'])->name('seller.create');
Route::post('/seller/store', [SellerController::class, 'store'])->name('seller.store');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Marketplace
        Route::get('/marketplace', [MarketplaceController::class, 'show'])->name('admin.marketplace');
        Route::get('/marketplace/store', [MarketplaceController::class, 'create'])->name('admin.marketplace.create');
        Route::get('/marketplace/update/{id_marketplace}', [MarketplaceController::class, 'edit'])->name('admin.marketplace.update');
        Route::post('/marketplace/store', [MarketplaceController::class, 'store'])->name('admin.marketplace.store');
        Route::post('/marketplace/delete', [MarketplaceController::class, 'delete'])->name('admin.marketplace.delete');

        Route::get('/users', [UserController::class, 'users'])->name('admin.users');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
