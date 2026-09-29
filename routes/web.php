<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\DepositController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/torneios', [HomeController::class, 'tournaments'])->name('tournaments.page');
Route::get('/missoes', [HomeController::class, 'missions'])->name('missions.page');


// Auth Routes (AJAX)
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/cpf/validate', [AuthController::class, 'validateCPF'])->name('cpf.validate');
Route::post('/password/forgot', [AuthController::class, 'sendPasswordResetCode'])->name('password.forgot');
Route::post('/password/reset', [AuthController::class, 'resetPasswordWithCode'])->name('password.reset');
Route::post('/deposit/create', [DepositController::class, 'create'])->name('deposit.create')->middleware('auth');
Route::get('/deposit/status/{id}', [DepositController::class, 'checkStatus'])->name('deposit.status')->middleware('auth');
Route::post('/coupon/validate', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->name('coupon.validate')->middleware('auth');
Route::get('/daily-roulette/status', [\App\Http\Controllers\DailyRouletteController::class, 'status'])->name('daily-roulette.status')->middleware('auth');
Route::post('/daily-roulette/spin', [\App\Http\Controllers\DailyRouletteController::class, 'spin'])->name('daily-roulette.spin')->middleware('auth');

// User Account Routes (AJAX)
Route::middleware(['auth'])->group(function () {
    Route::post('/withdrawal/request', [DepositController::class, 'requestWithdrawal'])->name('withdrawal.request');
    Route::post('/user/update-profile', [AuthController::class, 'updateProfile'])->name('user.update-profile');
    Route::post('/user/change-password', [AuthController::class, 'changePassword'])->name('user.change-password');
    Route::get('/user/history', [DepositController::class, 'getTransactionHistory'])->name('user.history');
    Route::get('/user/notifications', [\App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('user.notifications');
    Route::post('/user/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('user.notifications.read-all');
    Route::get('/vip/status', [\App\Http\Controllers\VipController::class, 'status'])->name('vip.status');
    Route::post('/vip/exchange', [\App\Http\Controllers\VipController::class, 'exchange'])->name('vip.exchange');
    
    // Affiliate Routes
    Route::get('/affiliate/stats', [\App\Http\Controllers\AffiliateController::class, 'getStats'])->name('affiliate.stats');
    Route::post('/affiliate/transfer', [\App\Http\Controllers\AffiliateController::class, 'transferCommission'])->name('affiliate.transfer');
});

// Didit KYC Routes
Route::post('/kyc/session', [KycController::class, 'createSession'])->name('kyc.session')->middleware('auth');
Route::post('/kyc/submit', [KycController::class, 'submitSession'])->name('kyc.submit')->middleware('auth');
Route::post('/kyc/webhook', [KycController::class, 'handleWebhook'])->name('kyc.webhook');

// HeartPay Webhook Route
Route::post('/webhook/heartpay', [DepositController::class, 'handleHeartpayWebhook'])->name('webhook.heartpay');

// PlayFiver Webhook Route
Route::post('/webhook/playfiver', [\App\Http\Controllers\PlayFiverController::class, 'handleCallback'])->name('webhook.playfiver');

// MAX API GAMES Webhook Route
Route::post('/webhook/maxapigames', [\App\Http\Controllers\MaxApiGamesController::class, 'handleCallback'])->name('webhook.maxapigames');

// Game Routes (AJAX)
Route::post('/game/bet', [GameController::class, 'placeBet'])->name('game.bet');
Route::post('/game/payout', [GameController::class, 'claimPayout'])->name('game.payout');
Route::get('/user/balance', [GameController::class, 'getBalance'])->name('user.balance');
Route::get('/game/launch/{slug}', [GameController::class, 'launch'])->name('game.launch');
Route::get('/esportes', [GameController::class, 'sports'])->name('game.sports');
Route::get('/tournaments/active', [GameController::class, 'getActiveTournaments'])->name('tournaments.active')->middleware('auth');
Route::get('/search-games', [GameController::class, 'searchGames'])->name('games.search');
Route::get('/missions/active', [GameController::class, 'getActiveMissions'])->name('missions.active')->middleware('auth');
Route::post('/missions/claim', [GameController::class, 'claimMissionReward'])->name('missions.claim')->middleware('auth');

// Admin Auth Routes
Route::get('/' . config('admin.panel_path') . '/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/' . config('admin.panel_path') . '/login', [AdminController::class, 'login']);
Route::match(['get', 'post'], '/' . config('admin.panel_path') . '/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin.auth'])->prefix(config('admin.panel_path'))->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.redirect');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/affiliates', [AdminController::class, 'affiliates'])->name('affiliates');
    Route::post('/users/{id}/balance', [AdminController::class, 'updateUserBalance'])->name('users.balance');
    Route::post('/users/{id}/password', [AdminController::class, 'updateUserPassword'])->name('users.password');
    Route::post('/users/{id}/commission', [AdminController::class, 'updateUserCommission'])->name('users.commission');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // KYC Management
    Route::get('/kyc', [KycController::class, 'adminIndex'])->name('kyc');
    Route::post('/kyc/{id}/status', [KycController::class, 'adminUpdateStatus'])->name('kyc.status');

    // Deposits Management
    Route::get('/deposits', [AdminController::class, 'deposits'])->name('deposits');
    Route::post('/deposits/{id}/approve', [AdminController::class, 'approveDeposit'])->name('deposits.approve');
    Route::post('/deposits/{id}/reject', [AdminController::class, 'rejectDeposit'])->name('deposits.reject');

    // Withdrawals Management
    Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
    Route::post('/withdrawals/{id}/approve', [AdminController::class, 'approveWithdrawal'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('withdrawals.reject');

    // System Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/test-email', [AdminController::class, 'sendTestEmail'])->name('settings.test-email');

    // Metrics Page
    Route::get('/metrics', [AdminController::class, 'metrics'])->name('metrics');

    // Banners / Personalization
    Route::get('/personalization', [AdminController::class, 'personalization'])->name('personalization');
    Route::post('/personalization/banner', [AdminController::class, 'storeBanner'])->name('personalization.banner.store');
    Route::post('/personalization/banner/update/{id}', [AdminController::class, 'updateBanner'])->name('personalization.banner.update');
    Route::delete('/personalization/banner/{id}', [AdminController::class, 'deleteBanner'])->name('personalization.banner.destroy');
    Route::post('/personalization/colors', [AdminController::class, 'updateColors'])->name('personalization.colors.update');
    Route::post('/personalization/colors/reset', [AdminController::class, 'resetColors'])->name('personalization.colors.reset');

    // Category Buttons / Games Management
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories/store', [AdminController::class, 'storeCategoryButton'])->name('categories.store');
    Route::post('/categories/update/{id}', [AdminController::class, 'updateCategoryButton'])->name('categories.update');
    Route::delete('/categories/destroy/{id}', [AdminController::class, 'deleteCategoryButton'])->name('categories.destroy');
    Route::post('/categories/reset', [AdminController::class, 'resetCategories'])->name('categories.reset');

    // Homepage Category Sections Management (CRUD)
    Route::get('/categories-manage', [AdminController::class, 'categoriesManage'])->name('categories_manage');
    Route::post('/categories-manage/store', [AdminController::class, 'storeCategory'])->name('categories_manage.store');
    Route::post('/categories-manage/update/{id}', [AdminController::class, 'updateCategory'])->name('categories_manage.update');
    Route::post('/categories-manage/reorder', [AdminController::class, 'reorderCategories'])->name('categories_manage.reorder');
    Route::post('/categories-manage/bulk-reorder', [AdminController::class, 'bulkReorderCategories'])->name('categories_manage.bulk_reorder');
    Route::delete('/categories-manage/destroy/{id}', [AdminController::class, 'deleteCategory'])->name('categories_manage.destroy');

    // Tournaments Management (CRUD)
    Route::get('/tournaments', [AdminController::class, 'tournaments'])->name('tournaments');
    Route::post('/tournaments/store', [AdminController::class, 'storeTournament'])->name('tournaments.store');
    Route::post('/tournaments/update/{id}', [AdminController::class, 'updateTournament'])->name('tournaments.update');
    Route::delete('/tournaments/destroy/{id}', [AdminController::class, 'deleteTournament'])->name('tournaments.destroy');

    // Missions Management (CRUD)
    Route::get('/missions', [AdminController::class, 'missions'])->name('missions');
    Route::post('/missions/store', [AdminController::class, 'storeMission'])->name('missions.store');
    Route::post('/missions/update/{id}', [AdminController::class, 'updateMission'])->name('missions.update');
    Route::delete('/missions/destroy/{id}', [AdminController::class, 'deleteMission'])->name('missions.destroy');

    // New Games Management (CRUD)
    Route::get('/games', [AdminController::class, 'games'])->name('games');
    Route::post('/games/bulk', [AdminController::class, 'bulkActionGames'])->name('games.bulk');
    Route::post('/games/bulk-import', [AdminController::class, 'bulkImportGames'])->name('games.bulk-import');
    Route::post('/games/store', [AdminController::class, 'storeGame'])->name('games.store');
    Route::post('/games/update/{id}', [AdminController::class, 'updateGame'])->name('games.update');
    Route::delete('/games/destroy/{id}', [AdminController::class, 'deleteGame'])->name('games.destroy');
    Route::get('/games/search-provider', [AdminController::class, 'searchProviderGames'])->name('games.search-provider');

    // PlayFiver API Integration Routes
    Route::get('/playfiver/providers', [AdminController::class, 'getPlayFiverProviders'])->name('playfiver.providers');
    Route::get('/playfiver/games', [AdminController::class, 'getPlayFiverGames'])->name('playfiver.games');

    // MAX API GAMES API Integration Routes
    Route::get('/maxapigames/providers', [AdminController::class, 'getMaxApiGamesProviders'])->name('maxapigames.providers');
    Route::get('/maxapigames/games', [AdminController::class, 'getMaxApiGamesGames'])->name('maxapigames.games');

    // Coupons Management (CRUD)
    Route::get('/coupons', [\App\Http\Controllers\CouponController::class, 'index'])->name('coupons');
    Route::post('/coupons/store', [\App\Http\Controllers\CouponController::class, 'store'])->name('coupons.store');
    Route::post('/coupons/update/{id}', [\App\Http\Controllers\CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/destroy/{id}', [\App\Http\Controllers\CouponController::class, 'destroy'])->name('coupons.destroy');

    // VIP Levels Management (CRUD)
    Route::get('/vip-levels', [AdminController::class, 'vipLevels'])->name('vip-levels');
    Route::post('/vip-levels/store', [AdminController::class, 'storeVipLevel'])->name('vip-levels.store');
    Route::post('/vip-levels/update/{id}', [AdminController::class, 'updateVipLevel'])->name('vip-levels.update');
    Route::delete('/vip-levels/destroy/{id}', [AdminController::class, 'deleteVipLevel'])->name('vip-levels.destroy');
});
