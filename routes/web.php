<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use Illuminate\Support\Facades\Route;

// ─── Public ───
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
Route::get('/ads/{id}-{slug?}', [AdController::class, 'show'])->name('ads.show')->where('id', '[0-9]+');
Route::get('/sellers/{user}', [\App\Http\Controllers\SellerController::class, 'show'])->name('sellers.show');
Route::get('/category/{category}', [AdController::class, 'category'])->name('category.show');
Route::get('/locations/tehsils/{district}', [App\Http\Controllers\LocationController::class, 'getTehsils'])->name('locations.tehsils');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ur'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// ─── Guest Only ───
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Google Login
    Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialController::class, 'handleGoogleCallback']);

    // Password Reset
    Route::get('/password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

// ─── Authenticated ───
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

    // Ads CRUD
    Route::get('/post-ad', [AdController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::get('/my-ads', [AdController::class, 'myAds'])->name('ads.mine');
    Route::patch('/ads/{ad}/sold', [AdController::class, 'markAsSold'])->name('ads.sold');
    Route::get('/ads/{ad}/phone', [AdController::class, 'showPhone'])->name('ads.phone');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Chat
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{chat}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/chats/unread', [ChatController::class, 'unreadCount'])->name('chats.unread');
    Route::get('/chats/{chat}/poll', [MessageController::class, 'poll'])->name('messages.poll');

    // Payments
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Hissa Requests / Qurbani
    Route::get('/qurbani-bookings', [App\Http\Controllers\HissaRequestController::class, 'index'])->name('hissa_requests.index');
    Route::post('/hissa-requests', [App\Http\Controllers\HissaRequestController::class, 'store'])->name('hissa_requests.store');
    Route::patch('/hissa-requests/{hissaRequest}', [App\Http\Controllers\HissaRequestController::class, 'update'])->name('hissa_requests.update');
    Route::patch('/ads/{ad}/manual-book', [App\Http\Controllers\HissaRequestController::class, 'manualBook'])->name('hissa_requests.manual_book');
});

// ─── Admin ───
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/ads', [AdManagementController::class, 'index'])->name('ads.index');
        Route::patch('/ads/{ad}/approve', [AdManagementController::class, 'approve'])->name('ads.approve');
        Route::patch('/ads/{ad}/reject', [AdManagementController::class, 'reject'])->name('ads.reject');
        Route::delete('/ads/{ad}', [AdManagementController::class, 'destroy'])->name('ads.destroy');

        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/ban', [UserManagementController::class, 'ban'])->name('users.ban');

        // Admin Chat Management
        Route::get('/chats', [ChatController::class, 'adminIndex'])->name('chats.index');
        Route::get('/chats/{user}', [ChatController::class, 'adminShow'])->name('chats.show');
        Route::post('/chats/{user}/messages', [MessageController::class, 'adminStore'])->name('messages.store');
        Route::get('/chats/{user}/poll', [MessageController::class, 'adminPoll'])->name('messages.poll');

        // Payments Management
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::patch('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
        Route::patch('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

        // Category Management
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::post('categories/{category}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('categories.toggle');

        // Location Management
        Route::get('/locations', [\App\Http\Controllers\Admin\LocationController::class, 'index'])->name('locations.index');
        Route::post('/locations/districts', [\App\Http\Controllers\Admin\LocationController::class, 'storeDistrict'])->name('locations.districts.store');
        Route::delete('/locations/districts/{district}', [\App\Http\Controllers\Admin\LocationController::class, 'destroyDistrict'])->name('locations.districts.destroy');
        Route::post('/locations/tehsils', [\App\Http\Controllers\Admin\LocationController::class, 'storeTehsil'])->name('locations.tehsils.store');
        Route::delete('/locations/tehsils/{tehsil}', [\App\Http\Controllers\Admin\LocationController::class, 'destroyTehsil'])->name('locations.tehsils.destroy');
    });

// ─── Cache Clear Helper (for shared hosting) ┇
Route::get('/clear-caches', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    return 'Caches cleared.';
});

// ─── Super Fix Route (Shared Hosting) ───
Route::get('/fix-everything', function () {
    try {
        // 1. Clear everything
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');

        // 2. Seed Categories if empty
        if (\App\Models\Category::count() == 0) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'CategorySeeder', '--force' => true]);
        }

        return "✅ Everything Fixed! Cache cleared and Categories checked. Your site is ready.";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});
