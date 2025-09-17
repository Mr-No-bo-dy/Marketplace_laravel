<?php

use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\MarketplaceController;
use App\Http\Controllers\Admin\ProducerController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\ClientController as SiteClientController;
use App\Http\Controllers\Site\OrderController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ReviewController as SiteReviewController;
use App\Http\Controllers\Site\SellerController as SiteSellerController;
use Illuminate\Support\Facades\Route;

/*
| Web Routes:
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider
| and all of them will be assigned to the "web" middleware group.
*/

/* =================================== */
/*               Web Site              */
/* =================================== */

// Different actions
Route::controller(GeneralController::class)->group(function () {
    Route::get('locale/{locale}', 'switch')->name('locale.switch');
    Route::get('/', 'index')->name('index');
});

// Authentication
Route::controller(AuthController::class)->group(function () {
    Route::get('/registration_seller', 'registerSeller')->name('registration_seller');
    Route::post('/registration_seller', 'storeSeller')->name('registration_seller');
    Route::get('/registration_client', 'registerClient')->name('registration_client');
    Route::post('/registration_client', 'storeClient')->name('registration_client');
    Route::get('/auth', 'auth')->name('auth');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/log_out', 'logout')->name('log_out');
});

// Products
Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'index')->name('product');
    Route::post('/product', 'index')->name('product');
    Route::get('/product/show/{id_product}', 'show')->name('product.show')->whereNumber('id_product');
});

// Cart
Route::controller(CartController::class)->group(function () {
    Route::post('/cart/add', 'store')->name('cart.add');
    Route::get('/cart', 'index')->name('cart');
    Route::patch('/cart/{id_product}', 'update')->name('cart.update')->whereNumber('id_product');
    Route::delete('/cart/{id_product}', 'delete')->name('cart.delete')->whereNumber('id_product');
});

// Order
Route::controller(OrderController::class)->group(function () {
    Route::get('/order/create', 'create')->name('order.create');
    Route::post('/order/make', 'store')->name('order.make');
});

// Client
Route::middleware('authClient')->group(function () {
    Route::controller(SiteClientController::class)->group(function () {
        Route::get('/client/personal', 'show')->name('client.personal');
        Route::get('/client/edit/{id_client}', 'edit')->name('client.edit')->whereNumber('id_client');
        Route::patch('/client/update/{client}', 'update')->name('client.update')->whereNumber('client');
        Route::patch('/client/updatePass/{client}', 'updatePass')->name('client.updatePass')->whereNumber('client');
        Route::delete('/client/delete', 'destroy')->name('client.delete');
    });
    Route::controller(SiteReviewController::class)->group(function () {
        Route::post('/review/add', 'store')->name('review.add');
        Route::patch('/review/update', 'update')->name('review.update');
        Route::delete('/review/destroy', 'destroy')->name('review.destroy');
    });
});

// Seller
Route::middleware('authSeller')->group(function () {
    Route::controller(SiteSellerController::class)->group(function () {
        Route::get('/seller/personal', 'show')->name('seller.personal');
        Route::get('/seller/edit/{id_seller}', 'edit')->name('seller.edit')->whereNumber('id_seller');
        Route::patch('/seller/update/{seller}', 'update')->name('seller.update')->whereNumber('seller');
        Route::patch('/seller/updatePass/{seller}', 'updatePass')->name('seller.updatePass')->whereNumber('seller');
        Route::delete('/seller/delete', 'destroy')->name('seller.delete');
    });
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/my_products', 'sellerProducts')->name('product.my_products');
        Route::get('/product/create', 'create')->name('product.create');
        Route::post('/product/store', 'store')->name('product.store');
        Route::get('/product/edit/{id_product}', 'edit')->name('product.edit')->whereNumber('id_product');
        Route::patch('/product/update/{product}', 'update')->name('product.update')->whereNumber('product');
        Route::delete('/product/delete', 'destroy')->name('product.delete');
        Route::patch('/product/restore', 'restore')->name('product.restore');
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order/my_orders', 'index')->name('order.my_orders');
        Route::patch('/order/my_orders', 'update')->name('order.my_orders');
    });
});

/* =================================== */
/*             Admin panel             */
/* =================================== */
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Admins
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'dashboard')->name('admin.dashboard');
        Route::get('/admins', 'admins')->name('admin.admins');
        Route::delete('/admins/delete', 'destroy')->name('admin.admins.delete');
        Route::patch('/admins/restore', 'restore')->name('admin.admins.restore');
    });

    // Markets
    Route::controller(MarketplaceController::class)->group(function () {
        Route::get('/marketplace', 'index')->name('admin.marketplace');
        Route::get('/marketplace/create', 'create')->name('admin.marketplace.create');
        Route::post('/marketplace/store', 'store')->name('admin.marketplace.store');
        Route::get('/marketplace/edit/{id_marketplace}', 'edit')->name('admin.marketplace.edit')->whereNumber('id_marketplace');
        Route::patch('/marketplace/update', 'update')->name('admin.marketplace.update');
        Route::delete('/marketplace/delete', 'destroy')->name('admin.marketplace.delete');
        Route::patch('/marketplace/restore', 'restore')->name('admin.marketplace.restore');
    });

    // Producers
    Route::controller(ProducerController::class)->group(function () {
        Route::get('/producer', 'index')->name('admin.producer');
        Route::get('/producer/create', 'create')->name('admin.producer.create');
        Route::post('/producer/store', 'store')->name('admin.producer.store');
        Route::get('/producer/edit/{id_producer}', 'edit')->name('admin.producer.edit')->whereNumber('id_producer');
        Route::patch('/producer/update', 'update')->name('admin.producer.update');
        Route::delete('/producer/delete', 'destroy')->name('admin.producer.delete');
        Route::patch('/producer/restore', 'restore')->name('admin.producer.restore');
    });

    // Categories
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'index')->name('admin.category');
        Route::get('/category/create', 'create')->name('admin.category.create');
        Route::post('/category/store', 'store')->name('admin.category.store');
        Route::get('/category/edit/{id_category}', 'edit')->name('admin.category.edit')->whereNumber('id_category');
        Route::patch('/category/update', 'update')->name('admin.category.update');
        Route::delete('/category/delete', 'destroy')->name('admin.category.delete');
        Route::patch('/category/restore', 'restore')->name('admin.category.restore');
    });

    // Subcategories
    Route::controller(SubcategoryController::class)->group(function () {
        Route::get('/subcategory', 'index')->name('admin.subcategory');
        Route::get('/subcategory/create', 'create')->name('admin.subcategory.create');
        Route::post('/subcategory/store', 'store')->name('admin.subcategory.store');
        Route::get('/subcategory/edit/{id_subcategory}', 'edit')->name('admin.subcategory.edit')->whereNumber('id_subcategory');
        Route::patch('/subcategory/update', 'update')->name('admin.subcategory.update');
        Route::delete('/subcategory/delete', 'destroy')->name('admin.subcategory.delete');
        Route::patch('/subcategory/restore', 'restore')->name('admin.subcategory.restore');
    });

    // Administrating Sellers, Clients & Reviews
    Route::controller(AdminSellerController::class)->group(function () {
        Route::get('/seller', 'index')->name('admin.seller');
        Route::delete('/seller/block', 'block')->name('admin.seller.block');
        Route::patch('/seller/unblock', 'unblock')->name('admin.seller.unblock');
    });
    Route::controller(AdminClientController::class)->group(function () {
        Route::get('/client', 'index')->name('admin.client');
        Route::delete('/client/block', 'block')->name('admin.client.block');
        Route::patch('/client/unblock', 'unblock')->name('admin.client.unblock');
    });
    Route::controller(AdminReviewController::class)->group(function () {
        Route::get('/reviews', 'index')->name('admin.reviews');
        Route::patch('/review/change', 'change')->name('admin.review.change');
    });
});

require __DIR__ . '/auth.php';
