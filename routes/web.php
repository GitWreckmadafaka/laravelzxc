<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CreateProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderHController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarouselController;

Route::get('/', function () {
    return redirect()->route('login.form');
});

Route::get('/home', [AuthController::class, 'home'])->middleware('auth')->name('home');

// Registration routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Email verification route
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

// OTP routes
Route::get('/otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile routes
Route::get('/profile/edit', [AuthController::class, 'showEditProfileForm'])->middleware('auth')->name('profile.edit');
Route::post('/profile/edit', [AuthController::class, 'updateProfile'])->middleware('auth')->name('profile.update'); // Name this route for better clarity

// Request profile update and send OTP
Route::post('/profile/edit', [AuthController::class, 'requestProfileUpdate'])->middleware('auth');

// Show OTP verification form for profile update
Route::get('/profile/otp', [AuthController::class, 'showOtpProfileUpdateForm'])->name('otp.profile.update.form');

// Verify the OTP and update the profile
Route::post('/profile/otp/verify', [AuthController::class, 'verifyOtpForProfileUpdate'])->name('otp.profile.update.verify');

// Password reset routes
Route::get('/password/reset', [AuthController::class, 'showResetRequestForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');

// Admin user management routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::post('/admin/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    Route::get('/admin/users', [ProductController::class, 'showUsersAndProducts'])->name('admin.users.index');
});

// Google login routes
Route::get('login/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('login/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// Home route for products
Route::get('/home', [ProductController::class, 'index']);
// Route to show products on the users index page

Route::get('admin/products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
Route::get('/create-product', [CreateProductController::class, 'create'])->name('create-product');
Route::get('/home', [ShopController::class, 'index'])->name('home');
Route::get('admin/products', [ProductController::class, 'index'])->name('admin.products.index');
// Route to delete a product
Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
Route::get('/admin/users-products', [ProductController::class, 'showUsersAndProducts'])->name('admin.users.products');

//new routes
Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');

Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
Route::get('/users/createproduct', [ProductController::class, 'create'])->name('admin.products.create');

// routes/web.php
Route::post('/admin/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('admin.users.toggleAdmin');

// Routes for Categories
Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
Route::post('admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
Route::delete('admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
Route::put('admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');

// Routes for Colors
Route::get('admin/colors/create', [ColorController::class, 'create'])->name('admin.colors.create');
Route::post('admin/colors', [ColorController::class, 'store'])->name('admin.colors.store');
Route::delete('admin/colors/{color}', [ColorController::class, 'destroy'])->name('admin.colors.destroy');
Route::put('admin/colors/{color}', [ColorController::class, 'update'])->name('admin.colors.update');

// Routes for Brand
Route::get('admin/brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
Route::post('admin/brands', [BrandController::class, 'store'])->name('admin.brands.store');
Route::delete('admin/brands/{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');
Route::put('admin/brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [UserController::class, 'dashboard'])->name('admin.dashboard');
});


Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');


Route::middleware(['auth'])->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'showCheckoutForm'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
});

Route::post('/process-payment', [ProcessController::class, 'handlePayment'])->name('payment.process');

Route::post('/capture-payment', [PaymentController::class, 'capturePayment'])->name('payment.capture');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/orders', [OrderHController::class, 'index'])->name('orders.index');
    Route::put('/orders/{order}/status', [OrderHController::class, 'updateStatus'])->name('orders.updateStatus');

});
    Route::delete('admin/orders/{id}/cancel', [OrderHController::class, 'cancel'])->name('admin.orders.cancel');
Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
Route::get('/wishlist', [WishlistController::class, 'showWishlist'])->name('wishlist.index');

Route::get('/generate-invoice/{orderId}', [InvoiceController::class, 'generateInvoice'])->name('generate.invoice');

Route::post('sales', [ReportController::class, 'index'])->name('sales.index');
Route::get('/sales', [ReportController::class, 'index'])->name('sales.index');
Route::post('/sales', [ReportController::class, 'create'])->name('sales.create');
Route::post('/orders/sales-data', [ReportController::class, 'getSalesData']);

Route::post('/orders/breakdown', [ReportController::class, 'breakdown']);

Route::get('/login/github', [GitHubController::class, 'redirectToGitHub'])->name('login.github');
Route::get('/login/github/callback', [GitHubController::class, 'handleGitHubCallback']);
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
// routes/web.php

Route::get('/admin/logs', [AdminController::class, 'viewLogs'])->name('admin.logs');



Route::get('/carousels', [CarouselController::class, 'index'])->name('carousels.index');

// Store new carousel item (add new item)
Route::post('/admin/carousels/store', [CarouselController::class, 'store'])->name('carousels.store');

// Delete carousel item
Route::delete('/admin/carousels/{id}', [CarouselController::class, 'destroy'])->name('carousels.destroy');
Route::get('/admin/brands/{categoryId}', [CategoryController::class, 'getBrandsByCategory']);
Route::get('/admin/brands/{categoryId}', [ProductController::class, 'getBrandsByCategory']);
