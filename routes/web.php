<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MarketplaceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProfileController;
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

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Marketplace
        Route::get('/marketplace', [MarketplaceController::class, 'view'])->name('admin.marketplace');
        Route::get('/marketplace/create', [MarketplaceController::class, 'store'])->name('admin.marketplace.store');
        Route::get('/marketplace/update/{id_marketplace}', [MarketplaceController::class, 'update'])->name('admin.marketplace.update');
        Route::post('/marketplace/create', [MarketplaceController::class, 'save'])->name('admin.marketplace.create');
        Route::post('/marketplace/delete', [MarketplaceController::class, 'delete'])->name('admin.marketplace.delete');

        Route::get('/users', [UserController::class, 'users'])->name('admin.users');
        Route::get('/sellers', [GeneralController::class, 'index'])->name('sellers.sellers');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
