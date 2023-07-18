<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MarketplaceController;
use App\Http\Controllers\Admin\ProducerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

/*
| Web Routes:
| Here is where you can register web routes for your application. 
| These routes are loaded by the RouteServiceProvider 
| and all of them will be assigned to the "web" middleware group.
*/

// Route::get('/', function () {
//    return view('welcome');
// });

// Site
Route::get('/', [GeneralController::class, 'index'])->name('index');
Route::get('/registration', [GeneralController::class, 'register'])->name('registration');
Route::post('/registration', [GeneralController::class, 'store'])->name('registration');
Route::get('/auth', [GeneralController::class, 'auth'])->name('auth');
Route::post('/auth', [GeneralController::class, 'auth'])->name('auth');

// Seller
Route::middleware('sellerAuth')->group(function () {
   Route::get('/seller', [SellerController::class, 'index'])->name('seller');
   Route::get('/seller/registration', [SellerController::class, 'create'])->name('seller.create');
   Route::post('/seller/store', [SellerController::class, 'store'])->name('seller.store');
   Route::get('/seller/edit/{id_seller}', [SellerController::class, 'edit'])->name('seller.edit');
   Route::post('/seller/update', [SellerController::class, 'update'])->name('seller.update');
   Route::post('/seller/delete', [SellerController::class, 'destroy'])->name('seller.delete');

   Route::get('/personal', [SellerController::class, 'show'])->name('personal');
});

// Admin Panel
Route::middleware('auth')->group(function () {
   Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
   Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
   Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

   Route::prefix('admin')->group(function () {
      Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

      // Marketplace
      Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('admin.marketplace');
      Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('admin.marketplace.create');
      Route::post('/marketplace/store', [MarketplaceController::class, 'store'])->name('admin.marketplace.store');
      Route::get('/marketplace/edit/{id_marketplace}', [MarketplaceController::class, 'edit'])->name('admin.marketplace.edit');
      Route::post('/marketplace/update', [MarketplaceController::class, 'update'])->name('admin.marketplace.update');
      Route::post('/marketplace/delete', [MarketplaceController::class, 'destroy'])->name('admin.marketplace.delete');

      // Producer
      Route::get('/producer', [ProducerController::class, 'index'])->name('admin.producer');
      Route::get('/producer/create', [ProducerController::class, 'create'])->name('admin.producer.create');
      Route::post('/producer/store', [ProducerController::class, 'store'])->name('admin.producer.store');
      Route::get('/producer/edit/{id_producer}', [ProducerController::class, 'edit'])->name('admin.producer.edit');
      Route::post('/producer/update', [ProducerController::class, 'update'])->name('admin.producer.update');
      Route::post('/producer/delete', [ProducerController::class, 'destroy'])->name('admin.producer.delete');
      
      // Category
      Route::get('/category', [CategoryController::class, 'index'])->name('admin.category');
      Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
      Route::post('/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
      Route::get('/category/edit/{id_category}', [CategoryController::class, 'edit'])->name('admin.category.edit');
      Route::post('/category/update', [CategoryController::class, 'update'])->name('admin.category.update');
      Route::post('/category/delete', [CategoryController::class, 'destroy'])->name('admin.category.delete');

      // Subcategory
      Route::get('/subcategory', [SubcategoryController::class, 'index'])->name('admin.subcategory');
      Route::get('/subcategory/create', [SubcategoryController::class, 'create'])->name('admin.subcategory.create');
      Route::post('/subcategory/store', [SubcategoryController::class, 'store'])->name('admin.subcategory.store');
      Route::get('/subcategory/edit/{id_subcategory}', [SubcategoryController::class, 'edit'])->name('admin.subcategory.edit');
      Route::post('/subcategory/update', [SubcategoryController::class, 'update'])->name('admin.subcategory.update');
      Route::post('/subcategory/delete', [SubcategoryController::class, 'destroy'])->name('admin.subcategory.delete');

      Route::get('/users', [UserController::class, 'users'])->name('admin.users');
   });
});

require __DIR__.'/auth.php';
