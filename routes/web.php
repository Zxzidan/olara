<?php

use App\Http\Controllers\AiScannerController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DropoffController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\RewardController;
use Illuminate\Support\Facades\Route;

// 1. Home / Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Authentication & Onboarding
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/demo-login', [AuthController::class, 'demoLogin'])->name('demo.login');

// 3. AI Waste Recognition Camera
Route::get('/scan', [AiScannerController::class, 'index'])->name('scanner.index');
Route::post('/scan/save', [AiScannerController::class, 'saveAnalysis'])->name('scanner.save');

// 4. On-Demand Pickup Service
Route::get('/pickup', [PickupController::class, 'index'])->name('pickup.index');
Route::post('/pickup', [PickupController::class, 'store'])->name('pickup.store');
Route::get('/pickup/{code}', [PickupController::class, 'show'])->name('pickup.show');

// 5. Drop-off Map & Directory
Route::get('/dropoff', [DropoffController::class, 'index'])->name('dropoff.index');

// 6. Rewards Wallet & Redemptions
Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
Route::post('/rewards/{id}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');

// 7. Ecological Impact & Carbon Analytics
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

// 8. Recycled Materials Marketplace
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::post('/marketplace/checkout', [MarketplaceController::class, 'checkout'])->name('marketplace.checkout');
Route::get('/marketplace/order/{orderNumber}', [MarketplaceController::class, 'orderDetail'])->name('marketplace.orderDetail');

// 9. Membership Plans
Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
Route::post('/membership/upgrade', [MembershipController::class, 'upgrade'])->name('membership.upgrade');
