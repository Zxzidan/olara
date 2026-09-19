<?php

use App\Http\Controllers\AiScannerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DropoffController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

// 1. Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// 2. Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/demo-login', [AuthController::class, 'demoLogin'])->name('demo.login');

// 3. Protected Application Routes (User Must Login to Access)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // AI Waste Recognition Camera
    Route::get('/scan', [AiScannerController::class, 'index'])->name('scanner.index');
    Route::post('/scan/save', [AiScannerController::class, 'saveAnalysis'])->name('scanner.save');

    // On-Demand Pickup Service
    Route::get('/pickup', [PickupController::class, 'index'])->name('pickup.index');
    Route::post('/pickup', [PickupController::class, 'store'])->name('pickup.store');
    Route::get('/pickup/{code}', [PickupController::class, 'show'])->name('pickup.show');

    // Drop-off Map & Directory
    Route::get('/dropoff', [DropoffController::class, 'index'])->name('dropoff.index');

    // Rewards Wallet & Redemptions
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{id}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');

    // Ecological Impact & Carbon Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Recycled Materials Marketplace
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/orders', [MarketplaceController::class, 'orders'])->name('marketplace.orders');
    Route::post('/marketplace/checkout', [MarketplaceController::class, 'checkout'])->name('marketplace.checkout');
    Route::get('/marketplace/order/{orderNumber}', [MarketplaceController::class, 'orderDetail'])->name('marketplace.orderDetail');
    Route::post('/marketplace/order/{orderNumber}/confirm', [MarketplaceController::class, 'confirmDelivery'])->name('marketplace.confirmDelivery');
    Route::post('/marketplace/order/{orderNumber}/status', [MarketplaceController::class, 'updateShippingStatus'])->name('marketplace.updateStatus');

    // Membership Plans
    Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
    Route::post('/membership/upgrade', [MembershipController::class, 'upgrade'])->name('membership.upgrade');
});
