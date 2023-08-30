<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MarketplaceController;
use App\Http\Controllers\Admin\ProducerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\ClientController;
use App\Http\Controllers\Site\OrderController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\SellerController;

/*
| Web Routes:
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider
| and all of them will be assigned to the "web" middleware group.
*/

// Automated Redirect of Homepage with localization:
Route::get('/', function () {
    return redirect(app()->getLocale());
});

// Routes without '{locale}' because of get-parameter conflict:
Route::controller(ProductController::class)->group(function () {
    Route::get('/product/show/{id_product}', 'show')->name('product.show');
});
Route::middleware('authClient')->group(function () {
    Route::controller(ClientController::class)->group(function () {
        Route::get('/client/edit/{id_client}', 'edit')->name('client.edit');
    });
});
Route::middleware('authSeller')->group(function () {
    Route::controller(SellerController::class)->group(function () {
        Route::get('/seller/edit/{id_seller}', 'edit')->name('seller.edit');
    });
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product/edit/{id_product}', 'edit')->name('product.edit');
    });
});
Route::middleware('auth')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::controller(MarketplaceController::class)->group(function () {
            Route::get('/marketplace/edit/{id_marketplace}', 'edit')->name('admin.marketplace.edit');
        });
        Route::controller(ProducerController::class)->group(function () {
            Route::get('/producer/edit/{id_producer}', 'edit')->name('admin.producer.edit');
        });
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/category/edit/{id_category}', 'edit')->name('admin.category.edit');
        });
        Route::controller(SubcategoryController::class)->group(function () {
            Route::get('/subcategory/edit/{id_subcategory}', 'edit')->name('admin.subcategory.edit');
        });
    });
});

// Site
Route::prefix('{locale}')->group(function () {

    Route::controller(GeneralController::class)->group(function () {
        Route::post('/switchLanguage', 'switchLanguage')->name('switchLanguage');
        Route::get('/', 'index')->name('index');
        Route::post('/product/cart', 'addToCart')->name('product.cart');
        Route::get('/registration', 'register')->name('registration');
        Route::post('/registration', 'store')->name('registration');
        Route::get('/registrationClient', 'registerClient')->name('registrationClient');
        Route::post('/registrationClient', 'storeClient')->name('registrationClient');
        Route::get('/auth', 'auth')->name('auth');
        Route::post('/auth', 'auth')->name('auth');
        Route::post('/log_out', 'logout')->name('log_out');
    });
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', 'index')->name('product');
        Route::post('/product', 'index')->name('product');
//            Route::get('/product/show/{id_product}', 'show')->name('product.show');
        Route::post('/product/addReview', 'storeReview')->name('product.addReview');
    });
    Route::controller(CartController::class)->group(function () {
        Route::get('cart', 'index')->name('cart');
        Route::post('cart', 'index')->name('cart');
    });
    Route::controller(OrderController::class)->group(function () {
        Route::get('create_order', 'create')->name('create_order');
        Route::post('make_order', 'store')->name('make_order');
    });

    Route::middleware('authClient')->group(function () {
        Route::controller(ClientController::class)->group(function () {
            Route::get('/client/personal', 'show')->name('client.personal');
//                Route::get('/client/edit/{id_client}', 'edit')->name('client.edit');
            Route::post('/client/update', 'update')->name('client.update');
            Route::post('/client/delete', 'destroy')->name('client.delete');
        });
    });

    Route::middleware('authSeller')->group(function () {
        Route::controller(SellerController::class)->group(function () {
            Route::get('/seller/personal', 'show')->name('seller.personal');
            Route::get('/seller/my_products', 'sellerProducts')->name('seller.my_products');
            Route::get('/seller/my_orders', 'sellerOrders')->name('seller.my_orders');
            Route::post('/seller/my_orders', 'sellerOrdersUpdate')->name('seller.my_orders');
//                Route::get('/seller/edit/{id_seller}', 'edit')->name('seller.edit');
            Route::post('/seller/update', 'update')->name('seller.update');
            Route::post('/seller/delete', 'destroy')->name('seller.delete');
        });
        Route::controller(ProductController::class)->group(function () {
            Route::get('/product/create', 'create')->name('product.create');
            Route::post('/product/store', 'store')->name('product.store');
//                Route::get('/product/edit/{id_product}', 'edit')->name('product.edit');
            Route::post('/product/update', 'update')->name('product.update');
            Route::post('/product/delete', 'destroy')->name('product.delete');
        });
    });

    // Admin Panel
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::prefix('admin')->group(function () {
            Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

            Route::controller(MarketplaceController::class)->group(function () {
                Route::get('/marketplace', 'index')->name('admin.marketplace');
                Route::get('/marketplace/create', 'create')->name('admin.marketplace.create');
                Route::post('/marketplace/store', 'store')->name('admin.marketplace.store');
//                    Route::get('/marketplace/edit/{id_marketplace}', 'edit')->name('admin.marketplace.edit');
                Route::post('/marketplace/update', 'update')->name('admin.marketplace.update');
                Route::post('/marketplace/delete', 'destroy')->name('admin.marketplace.delete');
            });
            Route::controller(ProducerController::class)->group(function () {
                Route::get('/producer', 'index')->name('admin.producer');
                Route::get('/producer/create', 'create')->name('admin.producer.create');
                Route::post('/producer/store', 'store')->name('admin.producer.store');
//                    Route::get('/producer/edit/{id_producer}', 'edit')->name('admin.producer.edit');
                Route::post('/producer/update', 'update')->name('admin.producer.update');
                Route::post('/producer/delete', 'destroy')->name('admin.producer.delete');
            });
            Route::controller(CategoryController::class)->group(function () {
                Route::get('/category', 'index')->name('admin.category');
                Route::get('/category/create', 'create')->name('admin.category.create');
                Route::post('/category/store', 'store')->name('admin.category.store');
//                    Route::get('/category/edit/{id_category}', 'edit')->name('admin.category.edit');
                Route::post('/category/update', 'update')->name('admin.category.update');
                Route::post('/category/delete', 'destroy')->name('admin.category.delete');
            });
            Route::controller(SubcategoryController::class)->group(function () {
                Route::get('/subcategory', 'index')->name('admin.subcategory');
                Route::get('/subcategory/create', 'create')->name('admin.subcategory.create');
                Route::post('/subcategory/store', 'store')->name('admin.subcategory.store');
//                    Route::get('/subcategory/edit/{id_subcategory}', 'edit')->name('admin.subcategory.edit');
                Route::post('/subcategory/update', 'update')->name('admin.subcategory.update');
                Route::post('/subcategory/delete', 'destroy')->name('admin.subcategory.delete');
            });
            Route::controller(SellerController::class)->group(function () {
                Route::get('/seller', 'index')->name('admin.seller');
                Route::post('/seller/block', 'block')->name('admin.seller.block');
                Route::post('/seller/unblock', 'unblock')->name('admin.seller.unblock');
            });
            Route::controller(ReviewController::class)->group(function () {
                Route::get('/reviews', 'index')->name('admin.reviews');
                Route::post('/review/change', 'change')->name('admin.review.change');
            });

            Route::get('/users', [UserController::class, 'users'])->name('admin.users');
        });
    });
});

require __DIR__ . '/auth.php';
