<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\TrendController;
use App\Http\Controllers\Customer\CustomerBargainController;
use App\Http\Controllers\Customer\ChatController;
use App\Http\Controllers\Customer\BarterController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\TrendController as AdminTrendController;
use App\Http\Controllers\Admin\AdminBargainController;
use App\Http\Controllers\Admin\AdminBarterController;
use App\Http\Controllers\Admin\VerificationController;
use App\Http\Controllers\Customer\BarterItemController;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])->middleware('guest')->name('login');
    Route::post('/login', [AdminLoginController::class, 'store'])->middleware('guest');
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/trends', [AdminTrendController::class, 'index'])->name('trends.index');
        Route::post('/trends/import', [AdminTrendController::class, 'importCsv'])->name('trends.import');
        Route::get('/trends/{id}/edit', [AdminTrendController::class, 'edit'])->name('trends.edit');
        Route::put('/trends/{id}', [AdminTrendController::class, 'update'])->name('trends.update');
        Route::delete('/trends/{id}', [AdminTrendController::class, 'destroy'])->name('trends.destroy');
        Route::post('/trends/{id}/publish', [AdminTrendController::class, 'publish'])->name('trends.publish');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
        Route::put('/orders/{order}/reject', [AdminOrderController::class, 'rejectPayment'])->name('orders.reject'); 
        
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('/bargains', [AdminBargainController::class, 'index'])->name('bargains.index');
        Route::put('/bargains/{id}', [AdminBargainController::class, 'update'])->name('bargains.update');

        Route::get('verifications', [VerificationController::class, 'index'])->name('verify.index');
        Route::get('view-ktp/{filename}', [VerificationController::class, 'showKtp'])->name('view-ktp');
        Route::post('verifications/approve/{id}', [VerificationController::class, 'approve'])->name('verify.approve');
        Route::post('verifications/reject/{id}', [VerificationController::class, 'reject'])->name('verify.reject');

        Route::get('/barters', [AdminBarterController::class, 'index'])->name('barter.index');

        Route::post('/barters/verify-payment/{id}/{userType}', [AdminBarterController::class, 'verifyPayment'])->name('barter.verify-payment');
        Route::post('/barters/reject-payment/{id}/{userType}', [AdminBarterController::class, 'rejectPayment'])->name('barter.reject-payment');
        Route::get('/barters/{id}', [AdminBarterController::class, 'show'])->name('barter.show');
        Route::post('/barters/update-logistic/{id}/{userType}', [AdminBarterController::class, 'updateLogistic'])->name('barter.update-logistic');
    });
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/produk/{product}', [ShopController::class, 'show'])->name('product.show');
Route::get('/search', [ShopController::class, 'search'])->name('product.search');
Route::get('/kategori/{category}', [ShopController::class, 'showByCategory'])->name('category.show');
Route::get('/trends', [TrendController::class, 'index'])->name('trends.index');
Route::get('/trends/{id}', [TrendController::class, 'show'])->name('trends.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/payment/{order}', [CheckoutController::class, 'uploadProof'])->name('checkout.payment.upload');

    Route::get('/order-history', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('orders.complete');
    Route::post('/bargains', [CustomerBargainController::class, 'store'])->name('bargains.store');
    Route::get('/my-bargains', [CustomerBargainController::class, 'index'])->name('customer.bargains.index');
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/bargain', [CartController::class, 'addFromBargain'])->name('cart.add.bargain');
    
    Route::post('/trends/{id}/like', [HomeController::class, 'likeTrend'])->name('trends.like');
    Route::post('/trends/{id}/comment', [TrendController::class, 'storeComment'])->name('comments.store');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');    
    Route::get('/chat/{receiver_id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::get('/barter-area', [BarterController::class, 'index'])->name('barter.index');
    Route::post('/barter-area/store', [BarterController::class, 'store'])->name('barter.store');
    Route::get('/barter/{id}', [BarterController::class, 'show'])->name('barter.show');
    Route::post('/barter/request/{id}', [BarterController::class, 'sendRequest'])->name('barter.request.send'); 
    Route::get('/riwayat-barter', [BarterController::class, 'inbox'])->name('barter.inbox');
    Route::post('/barter/update-status/{id}', [BarterController::class, 'updateStatus'])->name('barter.update-status');
    Route::post('/barter/update-resi/{id}', [BarterController::class, 'updateResi'])->name('barter.update-resi');
    Route::post('/barter/confirm-arrival/{id}', [BarterController::class, 'confirmArrival'])->name('barter.confirm-arrival');
    Route::delete('/barter/item/{id}', [BarterController::class, 'destroy'])->name('barter.destroy');
    Route::patch('/barter/item/{id}', [BarterController::class, 'update'])->name('barter.update');
    Route::post('/barter/send-otp/{id?}', [BarterController::class, 'sendOtp'])->name('barter.send-otp');
    Route::post('/barter/verify-otp/{id}', [BarterController::class, 'verifyAcceptance'])->name('barter.verify-otp');
    Route::get('/barter/tracking/{id}', [BarterController::class, 'tracking'])->name('barter.tracking');
    Route::post('/barter/select-protection/{id}', [BarterController::class, 'selectProtection'])->name('barter.select-protection');
    Route::post('/barter/reject/{id}', [BarterController::class, 'rejectRequest'])->name('barter.reject');
    Route::post('/barter/complete/{id}', [BarterController::class, 'completeBarter'])->name('barter.complete');
    Route::post('/barter/upload-payment/{id}', [BarterController::class, 'uploadPayment'])->name('barter.upload-payment');
    Route::post('/barter/cancel/{id}', [BarterController::class, 'cancel'])->name('barter.cancel');


    Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.my-profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update-full', [ProfileController::class, 'updateFull'])->name('profile.update-full');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/verify-identity', [ProfileController::class, 'showVerificationForm'])->name('verification.form');
    Route::post('/verify-identity', [ProfileController::class, 'submitVerification'])->name('verification.submit');
    Route::get('/profile/{id}', [ProfileController::class, 'showPublicProfile'])->name('profile.public');
    Route::post('/my-closet/add', [BarterItemController::class, 'store'])->name('items.store');
    Route::delete('/my-closet/item/{id}', [BarterItemController::class, 'destroy'])->name('items.destroy');
    Route::patch('/my-closet/item/{id}', [BarterItemController::class, 'update'])->name('items.update');
});

require __DIR__.'/auth.php';